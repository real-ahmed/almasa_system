<?php

namespace App\Http\Controllers;

use App\Models\AdvancePayment;
use App\Models\Bonus;
use App\Models\User;
use Illuminate\Http\Request;

class AdvancePaymentController extends Controller
{
    public function index(Request $request, $employeeId)
    {

        $employee = User::findOrFail($employeeId);
        $pageTitle = "سجل استلاف " . $employee->name;
        $search = $request->input('search');
        $paymentsQuery = AdvancePayment::query()->where('employee_id', $employeeId);

        if ($search) {
            $paymentsQuery->where(function ($query) use ($search) {
                Where('amount', 'like', '%' . $search . '%')
                    ->orWhereDate('created_at', 'like', '%' . $search . '%');
            });
        }
        if (!auth()->user()->isreceptionist) {
            $paymentsQuery->where('employee_id', auth()->user()->id);
        }
        $payments = $paymentsQuery->orderBy('id', 'desc')->paginate(getPaginate());
        return view('advance_payment.index', compact('pageTitle', 'payments'));
    }

    public function save(Request $request, $id = null)
    {
        $this->validatePayment($request);
        AdvancePayment::updateOrCreate(
            ['id' => $id],
            [
                'employee_id' => $request->employee_id,
                'amount' => $request->amount,
                'note' => $request->note
            ]
        );

        return back()->with('success', 'تم حفظ السلفة');
    }


    private function validatePayment(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|numeric|min:0',
            'employee_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = User::find($value);
                    if (!$user || $user->status != 1) {
                        $fail('حساب الموظف غير مفعل');
                    }
                },
            ],
        ]);
    }

    public function delete($id)
    {
        $payment = AdvancePayment::findOrFail($id);
        $payment->delete();
        return back()->with('success', 'تم حذف السلفة');
    }
}
