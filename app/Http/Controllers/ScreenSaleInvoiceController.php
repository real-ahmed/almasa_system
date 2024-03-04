<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\SaleScreen;
use App\Models\Screen;
use App\Models\ScreenSaleInvoice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ScreenSaleInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = "فواتير بيع الشاشات";

        $search = $request->input('search');

        $invoicesQuery = ScreenSaleInvoice::query();

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
        return view('screen.sale.invoice.index', compact('invoices', 'pageTitle'));
    }


    public function model($id = null)
    {
        $pageTitle = 'فاتورة بيع شاشة';
        $invoice = null;
        if ($id)
            $invoice = ScreenSaleInvoice::findOrFail($id);
        $screens = Screen::where('status', 4)->get();
        $receptionists = User::where('role', 2)->get();
        return view('screen.sale.invoice.model', compact('invoice', 'pageTitle', "screens", 'receptionists'));
    }

    public function details($id=null)
    {
        $invoice = ScreenSaleInvoice::findOrFail($id);
        $pageTitle = 'وصل بيع شاشات  ' . $invoice->reference_number;

        $printInvoice = View::make('printer.invoices.salescreen', compact('invoice'))->render();;
        return view('screen.sale.invoice.details', compact('invoice', 'pageTitle', 'printInvoice'));
    }

    public function delete($id){
        $invoice = ScreenSaleInvoice::findOrFail($id);
        $ScreenIds = $invoice->screens->pluck('screen_id')->toArray();
        $screens = Screen::whereIn('id', $ScreenIds)->get();
        $this->deleteScreens($screens);
        $invoice->delete();
        return back()->with('success','تم الحذف و استرجاع الشاشات');
    }

    private function deleteScreens($screens)
    {
        foreach ($screens as $screen) {
            $screen->status = 4;
            $screen->save();
            SaleScreen::where('screen_id', $screen->id)->delete();
        }
    }

    public function save(Request $request, $id = null)
    {
        $this->validate($request, [
            'customer_phone' => 'required|string',
            'customer_name' => 'required|string',
            'receptionist_id' => 'required',
            'screens' => 'required|array',
            'screens.*.id' => 'sometimes|required|exists:screen_invoices,id',
            'screens.*.screen_id' => 'required|exists:screens,id',
            'screens.*.screen_sell_price' => 'required|numeric|min:0',

        ]);

        $customer = Customer::updateOrCreate(
            ['phone' => $request->input('customer_phone')],
            ['name' => $request->input('customer_name')]

        );

        if (!$id) {
            $referenceNumber = 'SCRSEL' . date('Ymd') . strtoupper(substr(uniqid(), 8));
        } else {
            $referenceNumber = ScreenSaleInvoice::findOrFail($id)->reference_number;
        }


        $receptionist = auth()->user()->isadmin ? $request->input('receptionist_id') : auth()->user()->id;


        $invoice = ScreenSaleInvoice::updateOrCreate(
            ['id' => $id],
            [
                'customer_id' => $customer->id,
                'receptionist_id' => $receptionist, // Make sure 'receptionist_id' is provided
                'reference_number' => $referenceNumber,
            ]
        );

        $savedScreenIds = [];
        foreach ($request->input('screens') as $screenData) {


            $screen = Screen::findOrFail($screenData['screen_id']);
            $screen->status = 5;
            $screen->save();

            SaleScreen::updateOrCreate(
                ['screen_id' => $screen->id],
                [
                    'price' => $screenData['screen_sell_price'],
                    'invoice_id' => $invoice->id
                ]
            );
            $savedScreenIds[] = $screen->id;
        }


        $existingScreenIds = $invoice->screens->pluck('screen_id')->toArray();

        $screensToDelete = array_diff($existingScreenIds, $savedScreenIds);
        $screens = Screen::whereIn('id', $screensToDelete)->get();



        $this->deleteScreens($screens);

        $invoice->total_amount = $invoice->screens->sum('price');
        $invoice->save();

        return redirect()->route('receptionist.sale.screen.details', $invoice->id)->with('success', 'تم حفظ الفاتورة');


    }
}
