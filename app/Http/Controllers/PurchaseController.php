<?php

namespace App\Http\Controllers;

use App\Models\ManualItemRequest;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\RepairType;
use App\Models\ScreenComponent;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;
use Faker\Core\File;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    public function index(Request $request){
        $pageTitle = "فواتير الشراء";

        $search = $request->input('search');

        $purchaseQuery = Purchase::query();

        if ($search) {
            $purchaseQuery->where(function ($query) use ($search) {
                $query->whereHas('supplier', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('warehouse', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhere('total_amount', 'like', '%' . $search . '%')
                    ->orWhere('reference_number', 'like', '%' . $search . '%')
                    ->orWhere('created_at', 'like', '%' . $search . '%');
            });
        }

        $purchases = $purchaseQuery->orderBy('id','desc')->paginate(getPaginate());
        return view("purchase.index",compact('pageTitle','purchases'));
    }



    public function model($id = null){
        $pageTitle = 'انشاء فاتورة شراء';
        $purchase = null;

        if($id){
            $purchase = Purchase::findOrFail($id);
            $pageTitle = 'تعديل فاتورة شراء  '.$purchase->reference_number;
        }
        $suppliers = Supplier::orderBy('id','desc')->get();
        $warehouses = Warehouse::where('type',1)->orderBy('id','desc')->get();
        $screenComponents = ScreenComponent::orderBy('id','desc')->get();

        $warehouseEmployees = User::where('role',3)->get();



        return view("purchase.model",compact('pageTitle','purchase','suppliers','warehouses','screenComponents','warehouseEmployees'));
    }

    public function save(Request $request, $id = null)
    {

        // Validate the request data
        $this->validate($request, [
            'supplier_id' => 'required|exists:suppliers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'warehouse_employee_id' => 'sometimes|exists:users,id',
            'purchase_items' => 'required|array',
            'purchase_items.*.id' => 'sometimes|required|exists:purchase_items,id', // added validation for item id
            'purchase_items.*.product_id' => 'required|exists:screen_components,id',
            'purchase_items.*.quantity' => 'required|numeric|min:0',
            'purchase_items.*.price' => 'required|numeric|min:0',
        ]);

        // Create or update the purchase
        $purchase = $id ? Purchase::findOrFail($id) : new Purchase();
        $purchase->supplier_id = $request->input('supplier_id');
        $purchase->warehouse_id = $request->input('warehouse_id');
        $purchase->warehouse_employee_id = auth()->user()->isadmin ? $request->input('warehouse_employee_id') : auth()->user()->id;

        // Generate a reference number (you can customize this based on your requirements)
        if (!$id) {
            $referenceNumber = 'PUR' . date('Ymd') . strtoupper(substr(uniqid(), 8));
            $purchase->reference_number = $referenceNumber;
        }

        $purchase->total_amount = 0.00; // Initialize total_amount
        // Get the IDs of the items in the request
        $requestedItemIds = collect($request->input('purchase_items'))->pluck('id')->filter();

        // Get the IDs of the items in the database for the same purchase
        $existingItemIds = $purchase->items->pluck('id');

        // Identify the items that need to be deleted
        $itemsToDelete = $existingItemIds->diff($requestedItemIds);

        // Delete the items that are not in the request
        if ($itemsToDelete && !$purchase->unlinked()){
            return back()->withErrors([ 'لا يمكن حذف العنصر لأنه مرتبط بعناصر أخرى']);
        }
        PurchaseItem::whereIn('id', $itemsToDelete)->delete();
        $purchase->save();

        // Update or create purchase items
        foreach ($request->input('purchase_items') as $itemData) {
            if($id && isset($itemData['id']) ){
                $purchaseItem =  PurchaseItem::findOrFail($itemData['id']);

            }else{
                $purchaseItem = new PurchaseItem();
            }

            $purchaseItem->screen_component_id = $itemData['product_id'];
            $oldQuantity = $purchaseItem->quantity; // Store the old quantity
            $purchaseItem->quantity = $itemData['quantity'];

            // Calculate the difference between old and new quantity
            $quantityDifference = $itemData['quantity'] - $oldQuantity;

            // Check if instock_quantity would go below zero
            if ($purchaseItem->instock_quantity + $quantityDifference < 0) {
                // Handle the error (e.g., display a message or throw an exception)
                return redirect()->back()->withErrors(['purchase_items' => 'لا يمكنك تعديل الكمية']);
            }

            // Update instock_quantity based on the difference between old and new quantity
            $purchaseItem->instock_quantity += $quantityDifference;

            $purchaseItem->price = $itemData['price'];
            $purchase->items()->save($purchaseItem);
            $this->deleteManualItemRequest($itemData['product_id']);
            $purchase->total_amount += $itemData['quantity'] * $itemData['price'];
        }
        $purchase->save();

        return redirect()->route('warehouse.employee.purchase.all')->with('success', 'تم حفظ الفاتورة');
    }
    public function delete($id)
    {
        $purchase = Purchase::findOrFail($id);

        // Check if there are any unlinked items
        if ($purchase->unlinked()) {
            // Delete all associated purchase items
            $purchase->items()->delete();

            // Delete the purchase itself
            $purchase->delete();

            return back()->with('success', 'تم حفظ البيانات');
        }
        return back()->with('exception', 'لا يمكن حذف البيانات الآن لأنها مرتبطة بعناصر أخرى');
    }
    public function getInvoice($id)
    {
        $purchase = Purchase::findOrFail($id);

        // You can customize this view file based on your actual data structure and views
        return view('purchase.invoice', ['purchase' => $purchase]);
    }

    public function printInvoice($id)
    {
        $purchase = Purchase::findOrFail($id);
        $content = \Illuminate\Support\Facades\View::make('printer.invoices.purchase', compact('purchase'))->render();
        return response()->json(['content' => $content]);
    }

    private function deleteManualItemRequest($componentId){
        $manualItemRequest = ManualItemRequest::where('component_id', $componentId)->first();

        if($manualItemRequest  ){
            if (isset($manualItemRequest->ScreenComponent->InstockQuantity)) {
                if ($manualItemRequest->ScreenComponent->InstockQuantity >= $manualItemRequest->requested_quantity) {
                    $manualItemRequest->delete();
                }else{
                    $manualItemRequest->delete();
                }
            }
        }
    }


}
