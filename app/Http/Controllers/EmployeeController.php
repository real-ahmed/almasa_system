<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeRepairBonus;
use App\Models\GeneralSetting;
use App\Models\RepairType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request, $type)
    {
        $pageTitle = "الموظفين";

        $search = $request->input('search');

        $employees = User::whereHas('employee', function ($query) use ($search) {
            if ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('salary', 'like', $search)
                    ->orWhere('phone', 'like', '%' . $search . '%');

            }
        })->where('role', $type)->orderBy('id', 'desc')->paginate(getPaginate());
        return view("employee.employees", compact('pageTitle', 'employees'));
    }


    public function createEmployee(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required',
            'email' => 'required|string|unique:users,email',
            'phone' => 'required|string',
            'role' => 'required|in:1,2,3', // Assuming role should be 1 or 2
            'salary' => 'required|numeric',
            'salary_date' => 'required|date_format:m/d/Y',
            'departure_time' => ['required', 'regex:/^(1[0-2]|0?[1-9]):([0-5][0-9])\s?(am|pm)$/i'],
            'arrival_time' => ['required', 'regex:/^(1[0-2]|0?[1-9]):([0-5][0-9])\s?(am|pm)$/i'],

        ]);


        $default_password = GeneralSetting::first()->default_new_user_pass;


        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->role = $request->input('role');
        $user->password = Hash::make($default_password);

        $user->save();

        $employee = new Employee();
        $employee->user_id = $user->id;
        $employee->salary = $request->input('salary');
        $employee->salary_date = \Carbon\Carbon::createFromFormat('m/d/Y', $request->input('salary_date'));
        $employee->arrival_time = Carbon::createFromFormat('h:i A', $request->input('arrival_time'));
        $employee->departure_time = Carbon::createFromFormat('h:i A', $request->input('departure_time'));
        $employee->save();

        return redirect()->back()->with('success', 'تم انشاء ملف موظف جديد');
    }


    public function updateSalary(Request $request, $id)
    {
        $request->validate([
            'salary' => 'required|numeric',
            'salary_date' => 'required|date_format:m/d/Y',
            'departure_time' => ['required', 'regex:/^(1[0-2]|0?[1-9]):([0-5][0-9])\s?(am|pm)$/i'],
            'arrival_time' => ['required', 'regex:/^(1[0-2]|0?[1-9]):([0-5][0-9])\s?(am|pm)$/i'],
        ]);
        $employee = User::findOrFail($id)->employee;
        $employee->salary = $request->input('salary');
        $employee->salary_date = Carbon::createFromFormat('m/d/Y', $request->input('salary_date'));
        $employee->arrival_time = Carbon::createFromFormat('h:i A', $request->input('arrival_time'));
        $employee->departure_time = Carbon::createFromFormat('h:i A', $request->input('departure_time'));
        $employee->save();
        return back()->with('success', 'تم تحديث البيانات');


    }


    public function updateRepairBonus(Request $request, $id)
    {
        $request->validate([
            'bonus' => 'required|array',
            'bonus.*' => 'required|numeric',
            'bonus_type' => 'required|array',
            'bonus_type.*' => ['required', Rule::in([0, 1])], // Assuming bonus_type can be either 0 or 1
        ]);


        foreach ($request->input('bonus') as $i => $bonus) {
            $bonusType = $request->input('bonus_type')[$i];

            $originalValues = RepairType::findOrFail($request->input('id')[$i]);


            if ($bonus != $originalValues->default_bonus or $bonusType != $originalValues->bonus_type) {

                EmployeeRepairBonus::updateOrCreate(
                    [
                        'user_id' => $id,
                        'repair_type_id' => $originalValues->id
                    ],
                    [
                        'bonus' => $bonus,
                        'bonus_type' => $bonusType
                    ]
                );
            }
        }

        return back()->with('success', 'تم تحديث البيانات');
    }


}
