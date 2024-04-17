<?php

namespace App\Http\Controllers;

use App\Models\Deduction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DeductionController extends Controller
{
    public function index(Request $request)
    {

        $pageTitle = "سجل الخصم";
        $search = $request->input('search');
        $fromDate = date('Y-m-d 00:00:00', strtotime(request()->input('from_date')));
        $toDate = date('Y-m-d 23:59:59', strtotime(request()->input('to_date')));
        $deductionQuery = Deduction::query();

        if ($search) {
            $deductionQuery->where(function ($query) use ($search) {
                $query->whereHas('employee', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                })
                    ->orWhere('amount', 'like', '%' . $search . '%')
                    ->orWhereDate('created_at', 'like', '%' . $search . '%');

            });
        }
        if (!auth()->user()->isreceptionist) {
            $deductionQuery->where('employee_id', auth()->user()->id);
        }

        if (request()->input('from_date') && request()->input('to_date')) {
            $deductionQuery->whereBetween('created_at', [$fromDate, $toDate]);
        }


        $deductions = $deductionQuery->orderBy('id', 'desc')->paginate(getPaginate());
        return view('deduction.index', compact('pageTitle', 'deductions'));
    }

    public function save(Request $request, $id = null)
    {
        $this->validateDeduction($request, $id);

        if ($id) {
            $this->validateBonusDeletion($id);
        }

        Deduction::updateOrCreate(
            ['id' => $id],
            [
                'employee_id' => $request->employee_id,
                'amount' => $request->amount
            ]
        );

        return back()->with('success', 'تم حفظ الخصم');
    }

    private function validateDeduction(Request $request, $id)
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

    private function validateBonusDeletion($id)
    {
        $deduction = Deduction::findOrFail($id);

        if (!$deduction->unlinked()) {
            throw ValidationException::withMessages(['لا يمكن تعديل الخصم بعد القبض']);
        }
    }


    public function delete($id)
    {
        $deduction = Deduction::findOrFail($id);
        if ($deduction->unlinked()) {
            $deduction->delete();
            return back()->with('success', 'تم حذف الخصم');
        }
        throw ValidationException::withMessages(['لا يمكن حذف الخصم بعد القبض']);
    }


}
