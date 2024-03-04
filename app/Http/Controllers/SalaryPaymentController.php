<?php

namespace App\Http\Controllers;

use App\Models\SalaryPayment;
use Illuminate\Http\Request;

class SalaryPaymentController extends Controller
{
    public function index(Request $request,$type=0)
    {
        $pageTitle = 'سجل الراتب';
        $salaryPaymentsQuery = SalaryPayment::query();
        $search = $request->input('search');
        if ($search) {
            $salaryPaymentsQuery->whereHas('user', function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', '%' . $search . '%');
            })
                ->orWhere('salary_amount', 'like', '%' . $search . '%')
                ->orWhere('bonuses_amount', 'like', '%' . $search . '%')
                ->orWhereDate('created_at', 'like', '%' . $search . '%');

        }
        if (!auth()->user()->isadmin) {
            $salaryPaymentsQuery->where('user_id', auth()->user()->id);
        }
        $salaryPayments = $salaryPaymentsQuery->where('status',$type)->orderBy('id', 'desc')->paginate(getPaginate());

        return view('salary.index', compact('pageTitle', 'salaryPayments'));

    }

    public function save(Request $request, $id = null)
    {
        $this->validate($request, [
            'salary_amount' => 'required|numeric|min:0',
            'bonuses_amount' => 'required|numeric|min:0',
            'status' => 'required|numeric|min:0'
        ]);

        $salaryPayment = SalaryPayment::find($id);

        if ($salaryPayment) {
            $salaryPayment->update([
                'salary_amount' => $request->salary_amount,
                'bonuses_amount' => $request->bonuses_amount,
                'status' => $request->status,
            ]);
        }

        return back()->with('success', 'تم تحديث الراتب');
    }
}
