<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Deduction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{

    public function arrivalRequests(Request $request)
    {
        return $this->getAttendanceRecords($request, 'طلبات الحضور', 0);
    }

    private function getAttendanceRecords(Request $request, $pageTitle, ?int $status)
    {
        $search = $request->input('search');
        $recordsQuery = Attendance::query();

        if ($search) {
            $recordsQuery->where(function ($query) use ($search) {
                $query->whereHas('employee', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                });
            });
        }

        if ($status !== null) {
            $recordsQuery->where('status', $status);
        } else {
            $recordsQuery->whereNotIn('status', [0, 1]);
        }

        $records = $recordsQuery->orderBy('id', 'desc')->paginate(getPaginate());

        return view('attendance.index', compact('records', 'pageTitle'));
    }

    public function departureRequests(Request $request)
    {
        return $this->getAttendanceRecords($request, 'طلبات الانصراف', 1);
    }

    public function recordHistory(Request $request)
    {
        return $this->getAttendanceRecords($request, 'سجل الحضور و الانصراف', null);
    }


    public function save(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        if ($attendance->status == 0) {
            return $this->recordArrival($request, $attendance);
        } elseif ($attendance->status == 1) {
            return $this->recordDeparture($request, $attendance);

        } else {
            return $this->edit($request, $attendance);
        }
    }

    private function recordArrival(Request $request, $attendance)
    {
        // Validate the incoming request
        $this->validate($request, [
            'arrival_time' => ['required', 'regex:/^(1[0-2]|0?[1-9]):([0-5][0-9])\s?(am|pm)$/i'],
        ]);


        $arrivalTime = Carbon::createFromFormat('h:i A', $request->input('arrival_time'));


        // Update the attendance record with arrival time and status
        $attendance->arrival_time = $arrivalTime;
        $attendance->status = 1; // Assuming 1 indicates 'Present'

        $attendance->save();

        return back()->with('success', 'تم حقظ البيانات');
    }

    private function recordDeparture(Request $request, $attendance)
    {
        $this->validate($request, [
            'departure_time' => ['required', 'regex:/^(1[0-2]|0?[1-9]):([0-5][0-9])\s?(am|pm)$/i'],
        ]);
        $departureTime = Carbon::createFromFormat('h:i A', $request->input('departure_time'));
        $attendance->departure_time = $departureTime;
        $attendance->status = 2;
        $attendance->save();

        $employeeArrivalTime = Carbon::parse($attendance->employee->employee->arrival_time);
        $employeeDepartureTime = Carbon::parse($attendance->employee->employee->departure_time);
        $requiredWorkHours = $employeeArrivalTime->diffInSeconds($employeeDepartureTime) / 3600;

        $arrivalTime = Carbon::parse($attendance->arrival_time);
        $departureTime = Carbon::parse($attendance->departure_time);
        $actualWorkHours = $arrivalTime->diffInSeconds($departureTime) / 3600;


        if ($actualWorkHours < $requiredWorkHours) {
            $deduction = Deduction::create(
                [
                    'employee_id' => $attendance->employee_id,
                    'amount' => ($attendance->employee->employee->salary /  (Carbon::now()->daysInMonth-4)) * ($requiredWorkHours - $actualWorkHours)
                ]
            );
            $attendance->deductions_id = $deduction->id;
            $attendance->status = 3;
            $attendance->save();
        }


        return back()->with('success', 'تم حقظ البيانات');

    }

    private function edit($request, $attendance)
    {
        $this->validate($request, [
            'departure_time' => ['required', 'regex:/^(1[0-2]|0?[1-9]):([0-5][0-9])\s?(am|pm)$/i'],
            'arrival_time' => ['required', 'regex:/^(1[0-2]|0?[1-9]):([0-5][0-9])\s?(am|pm)$/i'],
        ]);
        $departureTime = Carbon::createFromFormat('h:i A', $request->input('departure_time'));
        $attendance->departure_time = $departureTime;
        $arrivalTime = Carbon::createFromFormat('h:i A', $request->input('arrival_time'));
        $attendance->arrival_time = $arrivalTime;
        $attendance->save();

        $employeeArrivalTime = Carbon::parse($attendance->employee->employee->arrival_time);
        $employeeDepartureTime = Carbon::parse($attendance->employee->employee->departure_time);
        $requiredWorkHours = $employeeArrivalTime->diffInSeconds($employeeDepartureTime) / 3600;

        $arrivalTime = Carbon::parse($attendance->arrival_time);
        $departureTime = Carbon::parse($attendance->departure_time);
        $actualWorkHours = $arrivalTime->diffInSeconds($departureTime) / 3600;


        if ($actualWorkHours < $requiredWorkHours) {
            $deduction = Deduction::updateOrCreate(
                ['id' => $attendance->deductions_id],
                [
                    'employee_id' => $attendance->employee_id,
                    'amount' => ($attendance->employee->employee->salary /  (Carbon::now()->daysInMonth-4)) * ($requiredWorkHours - $actualWorkHours)
                ]
            );
            $attendance->deductions_id = $deduction->id;
            $attendance->status = 3;
        } else {
            $deduction = Deduction::find($attendance->deductions_id);
            if ($deduction) {
                $deduction->delete();
                $attendance->status = 2;
            }
        }
        $attendance->save();
        return back()->with('success', 'تم حقظ البيانات');
    }


}
