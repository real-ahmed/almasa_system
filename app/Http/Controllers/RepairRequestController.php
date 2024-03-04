<?php

namespace App\Http\Controllers;


use App\Models\Bonus;
use App\Models\PurchaseItem;
use App\Models\Repair;
use App\Models\RepairDeliver;
use App\Models\RepairScreenComponent;
use App\Models\RepairScreenService;
use App\Models\RepairType;
use App\Models\Screen;
use App\Models\ScreenBonus;
use App\Models\ScreenComponent;
use App\Models\ScreenPurchaseItemHistory;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Validation\ValidationException;

class RepairRequestController extends Controller
{
    public function index(Request $request, $type = 0)
    {
        $pageTitle = $type ? 'سجلات الصيانة' : 'طلبات الصيانة';

        $search = $request->input('search');

        $screensQuery = Screen::query();

        if ($search) {
            $screensQuery->where(function ($query) use ($search) {
                $query->whereHas('repairs.customer', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('repairs.receptionist', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('brand', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('warehouse', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('repairs', function ($subQuery) use ($search) {
                        $subQuery->where('reference_number', 'like', '%' . $search . '%');
                    })
                    ->orWhere('code', 'like', '%' . $search . '%')
                    ->orWhere('model', 'like', '%' . $search . '%')
                    ->orWhereDate('created_at', 'like', '%' . $search . '%');
            });
        }

//        if (!auth()->user()->isreceptionist) {
//            $screensQuery->where('engineer_maintenance_id', auth()->user()->id);
//        }

        $screens = $screensQuery->where('status', $type ? '<>' : '=', 0)
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());

        return view('repairrequest.index', compact('screens', 'pageTitle','type'));
    }

    public function model($id)
    {
        $screen = Screen::findOrFail($id);
        if (!auth()->user()->isreceptionist) {
            abort(403, 'Unauthorized');
        }
        $pageTitle = 'صيانة شاشة ' . $screen->code;
        $screenComponents = ScreenComponent::orderBy('id', 'desc')->get();
        $services = RepairType::orderBy('id', 'desc')->get();
        $engineers = User::where('role',1)->orderBy('id', 'desc')->get();

        return view("repairrequest.model", compact('pageTitle', 'screen', 'screenComponents', 'services','engineers'));
    }

    public function save(Request $request, $id = null)
    {

        $this->validateRequest($request);

        $screen = Screen::findOrFail($id);
        $total_amount = 0;


        if (isset($request->used_items)) {
            $requestedItems = collect($request->input('used_items'));

            // Find all used items related to the screen
            $existingItems = RepairScreenComponent::where('screen_id', $screen->id)->get();

            // Identify items to be deleted
            $itemsToDelete = $existingItems->filter(function ($existingItem) use ($requestedItems) {
                return !$requestedItems->pluck('id')->contains($existingItem->id);
            });

            // Delete items that are not present in the request
            foreach ($itemsToDelete as $itemToDelete) {
                $this->deleteItem($itemToDelete, $screen);
            }


            foreach ($request->input('used_items') as $itemData) {

                $usedItem = $this->findOrCreateUsedItem($itemData);
                $oldQuantity = $usedItem->quantity;
                $quantityChange = $itemData['quantity'] - $oldQuantity;

                if (ScreenComponent::findOrFail($itemData['product_id'])->instock_quantity < $quantityChange) {
                    throw ValidationException::withMessages(["الكمية غير متوفرة"]);

                }

                $usedItem->fill([
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                ]);
                $productRows = PurchaseItem::where('screen_component_id', $itemData['product_id'])
                    ->where('quantity', '>', 0)
                    ->orderBy('created_at')
                    ->get();


                if ($quantityChange > 0) {
                    foreach ($productRows as $row) {
                        $r = $this->decreaseInstockQuantity($row, $quantityChange, $screen);
                        $row->save();
                        if ($r == true) {
                            break;
                        }

                    }

                } elseif ($quantityChange < 0) {
                    $this->increaseInstockQuantity($itemData['product_id'], abs($quantityChange), $screen);
                }


                $usedItem->screen_id = $id;
                $usedItem->screen_component_id = $itemData['product_id'];

                $usedItem->save();
                $total_amount += $itemData['price'] * $itemData['quantity'];


            }
        }


        // Additional logic for service_items

        if (isset($request->service_items)) {
            $requestedItems = collect($request->input('service_items'));

            // Find all used items related to the screen
            $existingItems = RepairScreenService::where('screen_id', $screen->id)->get();

            // Identify items to be deleted
            $itemsToDelete = $existingItems->filter(function ($existingItem) use ($requestedItems) {
                return !$requestedItems->pluck('id')->contains($existingItem->id);
            });

            // Delete items that are not present in the request
            foreach ($itemsToDelete as $itemToDelete) {
                $itemToDelete->delete();
            }

            foreach ($request->input('service_items') as $serviceData) {
                if ($id && isset($serviceData['id'])) {
                    $serviceItem = RepairScreenService::findOrFail($serviceData['id']);

                } else {
                    $serviceItem = new RepairScreenService();
                }
                $serviceItem->repair_type_id = $serviceData['service_id'];
                $serviceItem->screen_id = $id;


                $serviceItem->price = $serviceData['price'];
                $serviceItem->engineer_maintenance_id = $serviceData['engineer_maintenance'];
                $serviceItem->save();
                $total_amount += $serviceItem['price'];
            }

        }


        $screen->repair_amount = $total_amount;
        $screen->save();
        if ($request->save_type == 1 || $request->save_type == 3) {
            $screen->status = $request->save_type;
            $screen->save();

            if ($request->save_type == 1) {
                //  add user bonus


                foreach ($screen->services as $service){
                    if($service->engineer_maintenance->status == 1){
                        $bonus = new Bonus();


                        $bonus->employee_id = $service->engineer_maintenance_id;
                        $bonus->amount = $service->engineer_maintenance->getScreenBonusAmount($screen);
                        $bonus->save();

                        $screenBonus = new ScreenBonus();
                        $screenBonus->screen_id = $screen->id;
                        $screenBonus->bonus_id = $bonus->id;
                        $screenBonus->save();
                    }
                }
//                if ($screen->engineer_maintenance->status == 1){
//                    $bonus = new Bonus();
//                    $bonus->employee_id = $screen->engineer_maintenance_id;
//                    $bonus->amount = $screen->engineer_maintenance->getBestScreenBonusAmount($screen);
//                    $bonus->save();
//
//                    $screenBonus = new ScreenBonus();
//                    $screenBonus->screen_id = $screen->id;
//                    $screenBonus->bonus_id = $bonus->id;
//                    $screenBonus->save();
//                }

            }

            $repair = Repair::find($screen->repairs->first()->id);
            if ($repair->screens->where('status', 0)->first() == null) {
                $deliver = new RepairDeliver();
                $deliver->repair_id = $repair->id;
                $deliver->reference_number = 'DEL' . date('Ymd') . strtoupper(substr(uniqid(), 8));
                $deliver->setTotal();
                $deliver->save();
            }


            return redirect(route('repair.request.details', $id))->with('success', 'تم الحفظ للتسليم');

        }
        $repair = Repair::find($screen->repairs->first()->id);
        if ($repair->screens->where('status', 0)->first() == null) {
            $deliver = RepairDeliver::find($repair->deliver->id);
            $deliver->setTotal();
            $deliver->save();
        }


        return back()->with('success', 'تم تحديث البيانات');
    }




    private function validateRequest(Request $request)
    {
        $this->validate($request, [
            'used_items' => 'array',
            'used_items.*.id' => 'sometimes|required|exists:repair_screen_components,id',
            'used_items.*.product_id' => 'required|exists:screen_components,id',
            'used_items.*.price' => 'required|numeric|min:0',
            'used_items.*.quantity' => 'required|numeric|min:0',

            'service_items' => 'array',
            'service_items.*.id' => 'sometimes|required|exists:repair_screen_services,id',
            'service_items.*.service_id' => 'required|exists:repair_types,id',
            'service_items.*.engineer_maintenance' => 'required|exists:users,id',
            'service_items.*.price' => 'required|numeric|min:0',
        ]);
    }

    private function deleteItem($item, $screen)
    {
        $this->increaseInstockQuantity($item['screen_component_id'], abs($item['quantity']), $screen);
        $item->delete();
    }

    private function increaseInstockQuantity($productId, $absQuantityChange, $screen)
    {
        $history = ScreenPurchaseItemHistory::where('screen_id', $screen->id)->whereHas('purchaseitem', function ($subQuery) use ($productId) {
            $subQuery->where('screen_component_id', $productId);
        })
            ->get();

        foreach ($history as $process) {

            if ($process->quantity >= $absQuantityChange) {
                $process->quantity -= $absQuantityChange;
                $productRow = PurchaseItem::where('id', $process->purchase_item_id)->first();
                $productRow->instock_quantity += $absQuantityChange;
                $productRow->save();
                break;
            } else {
                $productRow = PurchaseItem::where('id', $process->purchase_item_id)->first();
                $productRow->instock_quantity += $absQuantityChange;
                $productRow->save();
                $absQuantityChange -= $process->quantity;
                $process->quantity = 0;
            }
        }
    }

    private function findOrCreateUsedItem(array $itemData)
    {
        return isset($itemData['id'])
            ? RepairScreenComponent::findOrFail($itemData['id'])
            : new RepairScreenComponent();
    }

    private function decreaseInstockQuantity($row, $quantityChange, $screen)
    {
        if ($row->instock_quantity >= $quantityChange) {
            $row->instock_quantity -= $quantityChange;
            $this->updateHistory($row, $quantityChange, $screen);
            return true;
        } else {
            $this->updateHistory($row, $row->instock_quantity, $screen);
            $row->instock_quantity = 0;
            return false;
        }
    }

    private function updateHistory($row, $quantityChange, $screen)
    {
        $history = ScreenPurchaseItemHistory::where('screen_id', $screen->id)
            ->where('purchase_item_id', $row->id)
            ->first();

        if ($history) {
            // Update the existing history entry
            $history->quantity += $quantityChange;
            $history->save();
        } else {
            // Create a new history entry
            ScreenPurchaseItemHistory::create([
                'screen_id' => $screen->id,
                'purchase_item_id' => $row->id,
                'quantity' => $quantityChange,
            ]);
        }
    }

    public function details($id)
    {
        $screen = Screen::findOrFail($id);
        $pageTitle = 'معلومات الشاشة ' . $screen->code;
        return view("repairrequest.details", compact('pageTitle', 'screen'));
    }
}
