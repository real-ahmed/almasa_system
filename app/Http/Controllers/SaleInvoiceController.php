<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoicePurchaseItemHistory;
use App\Models\InvoiceScreenComponent;
use App\Models\PurchaseItem;
use App\Models\ScreenComponent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SaleInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'فواتير البيع';

        $search = $request->input('search');

        $invoicesQuery = Invoice::query();
        if ($search) {
            $invoicesQuery->where(function ($query) use ($search) {
                $query->whereHas('customer', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('receptionist', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', '%' . $search . '%');
                    })->orWhere('reference_number', 'like', '%' . $search . '%')
                    ->orWhere('total_amount', 'like', '%' . $search . '%')
                    ->orWhereDate('created_at', 'like', '%' . $search . '%');
            });
        }

        $invoices = $invoicesQuery->orderBy('id', 'desc')->paginate(getPaginate());

        return view('saleinvoice.index', compact('invoices', 'pageTitle'));
    }

    public function model($id = null)
    {
        $pageTitle = 'انشاء فاتورة بيع';
        $invoice = null;

        if ($id) {
            $invoice = Invoice::findOrFail($id);
            $pageTitle = 'تعديل فاتورة بيع  ' . $invoice->reference_number;
        }
        $screenComponents = ScreenComponent::orderBy('id', 'desc')->get();
        $receptionists = User::where('role', 2)->get();

        return view("saleinvoice.model", compact('pageTitle', 'invoice', 'receptionists', 'screenComponents'));
    }


    public function save(Request $request, $id = null)
    {

        $this->validateRequest($request);

        $customer = Customer::updateOrCreate(
            ['phone' => $request->input('customer_phone')],
            [
                'name' => $request->input('customer_name'),
                'address' => $request->input('customer_address')
            ]


        );

        $invoice = $id ? Invoice::find($id) : new Invoice();
        if (!$id) {
            $invoice->reference_number = 'SEL' . date('Ymd') . strtoupper(substr(uniqid(), 8));
        }
        $invoice->receptionist_id = auth()->user()->isadmin ? $request->input('receptionist_id') : auth()->user()->id;
        $invoice->customer_id = $customer->id;
        $invoice->total_amount = 10;
        $invoice->save();

        $requestedItems = collect($request->input('used_items'));
        $existingItems = InvoiceScreenComponent::where('invoice_id', $invoice->id)->get();
        // Identify items to be deleted
        $itemsToDelete = $existingItems->filter(function ($existingItem) use ($requestedItems) {
            return !$requestedItems->pluck('id')->contains($existingItem->id);
        });
        // Delete items that are not present in the request
        foreach ($itemsToDelete as $itemToDelete) {
            $this->deleteItem($itemToDelete, $invoice);
        }

        $total_amount = 0;
        foreach ($request->input('used_items') as $itemData) {
            $total_amount += $itemData['quantity'] * $itemData['price'];
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
                    $r = $this->decreaseInstockQuantity($row, $quantityChange, $invoice);
                    $row->save();
                    if ($r == true) {
                        break;
                    }

                }

            } elseif ($quantityChange < 0) {
                $this->increaseInstockQuantity($itemData['product_id'], abs($quantityChange), $invoice);
            }

            $usedItem->invoice_id = $invoice->id;
            $usedItem->screen_component_id = $itemData['product_id'];
            $usedItem->save();
        }

        $invoice->total_amount = $total_amount;
        $invoice->save();
        return redirect(route('receptionist.sale.details',$invoice->id))->with('success', 'تم تحديث البيانات');
    }

    private function validateRequest(Request $request)
    {
        $this->validate($request, [
            'customer_phone' => 'required|string',
            'customer_name' => 'required|string',
            'receptionist_id' => 'required',
            'used_items' => 'required|array',
            'used_items.*.id' => 'sometimes|required|exists:invoice_screen_components,id',
            'used_items.*.product_id' => 'required|exists:screen_components,id',
            'used_items.*.price' => 'required|numeric|min:0',
            'used_items.*.quantity' => 'required|numeric|min:0',

        ]);
    }

    private function deleteItem($item, $invoice)
    {
        $this->increaseInstockQuantity($item['screen_component_id'], abs($item['quantity']), $invoice);
        $item->delete();
    }
    private function increaseInstockQuantity($productId, $absQuantityChange, $invoice)
    {
        $history = InvoicePurchaseItemHistory::where('invoice_id', $invoice->id)->whereHas('purchaseitem', function ($subQuery) use ($productId) {
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
            ? InvoiceScreenComponent::findOrFail($itemData['id'])
            : new InvoiceScreenComponent();
    }
    private function decreaseInstockQuantity($row, $quantityChange, $invoice)
    {
        if ($row->instock_quantity >= $quantityChange) {
            $row->instock_quantity -= $quantityChange;
            $this->updateHistory($row, $quantityChange, $invoice);
            return true;
        } else {
            $this->updateHistory($row, $row->instock_quantity, $invoice);
            $row->instock_quantity = 0;
            return false;
        }
    }
    private function updateHistory($row, $quantityChange, $invoice)
    {
        $history = InvoicePurchaseItemHistory::where('invoice_id', $invoice->id)
            ->where('purchase_item_id', $row->id)
            ->first();

        if ($history) {
            // Update the existing history entry
            $history->quantity += $quantityChange;
            $history->save();
        } else {
            // Create a new history entry
            InvoicePurchaseItemHistory::create([
                'invoice_id' => $invoice->id,
                'purchase_item_id' => $row->id,
                'quantity' => $quantityChange,
            ]);
        }
    }
    public function details($id)
    {

        $invoice = Invoice::findOrFail($id);
        $pageTitle = 'فاتورة بيع' . $invoice->reference_number;
        $printInvoice = View::make('printer.invoices.sale', compact('invoice'))->render();
        return view('saleinvoice.details', compact('invoice','printInvoice', 'pageTitle'));
    }


    public function delete($id){
        $invoice =  Invoice::findorFail($id);
        $itemsToDelete = InvoiceScreenComponent::where('invoice_id', $invoice->id)->get();
        foreach ($itemsToDelete as $itemToDelete) {
            $this->deleteItem($itemToDelete, $invoice);
        }
        $invoice->delete();
        return back()->with('success','تم حذف الفاتورة');

    }

}
