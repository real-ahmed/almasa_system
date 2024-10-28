<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use App\Models\RepairDeliver;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class RepairDeliverController extends Controller
{
    public function index(Request $request, $type = 0)
    {
        $pageTitle = $type ? 'سجلات التسيم' : 'طلبات التسليم';


        $search = $request->input('search');

        $deliversQuery = RepairDeliver::query();

        if ($search) {
            $deliversQuery->where(function ($query) use ($search) {
                $query->whereHas('repair.customer', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('repair.receptionist', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('repair', function ($subQuery) use ($search) {
                        $subQuery->where('reference_number', 'like', '%' . $search . '%')
                            ->orWhereDate('created_at', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('repair.screens.engineer_receive', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhere('reference_number', 'like', '%' . $search . '%')
                    ->orWhereDate('created_at', 'like', '%' . $search . '%');
            });
        }


        $delivers = $deliversQuery->where('status', $type)
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());

        return view('deliver.index', compact('delivers', 'pageTitle', 'type'));

    }


    public function details($id = null)
    {

        $deliver = RepairDeliver::findOrFail($id);
        $pageTitle = 'وصل تسليم الصيانة  ' . $deliver->reference_number;
        $printDeliver = View::make('printer.invoices.deliver', compact('deliver'))->render();

        return view('deliver.details', compact('deliver', 'printDeliver', 'pageTitle'));

    }


    public function clickOnPrint($id = null): void
    {
        $deliver = RepairDeliver::findOrFail($id);
        if (!$deliver->invoice_print_date) {
            $deliver->invoice_print_date = Carbon::now();
            $deliver->save();
        }
    }


    public function save($id)
    {
        $deliver = RepairDeliver::findOrFail($id);
        $deliver->status = 1;
        $deliver->repair->screens->each(function ($screen) {
            $screen->update(['status' => 2]);
        });

        $deliver->save();

        return redirect(route('receptionist.repair.deliver.details', $deliver->id))->with('success', 'تم تسليم الفاتورة');

    }

    public function paid(Request $request, $id)
    {
        $deliver = RepairDeliver::findOrFail($id);
        $this->validate($request, [
            'amount' => 'required|numeric|min:1'
        ]);

        $rest = $this->addPayment($deliver, $request->amount);
        $general = GeneralSetting::first();

        return redirect(route('receptionist.repair.deliver.details', $deliver->id))
            ->with('successMessages', [
                'تم تسجيل الدفعة',
                'برجاء اعادة ' . $rest . " " . $general->money_sign . " الى العميل"
            ]);
    }

    public function addPayment($deliver, $amount)
    {
        $total_amount = $deliver->total_amount;
        $received_amount = ($deliver->received_amount + $deliver->repair->paid);
        $rest = 0;
        if ($amount + $received_amount > $total_amount) {

            $rest = ($amount + $received_amount) - $total_amount;

            $deliver->received_amount = $total_amount;

        } else {
            $deliver->received_amount += $amount;
        }
        $deliver->save();

        return $rest;
    }

    public function saveNote(Request $request, $id)
    {
        $deliver = RepairDeliver::findOrFail($id);
        $deliver->note = $request->note;
        $deliver->save();
        return redirect()->back()->with('success', 'تم حفظ الملاحظة');

    }


}
