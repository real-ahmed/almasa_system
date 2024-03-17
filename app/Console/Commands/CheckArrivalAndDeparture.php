<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Deduction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckArrivalAndDeparture extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-arrival-and-departure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->saveDeparture();
        $this->createAttendance();

    }

    private function saveDeparture()
    {
        $departures = Attendance::whereIn('status', [1, 0])->get();
        foreach ($departures as $departure) {
            if ($departure->status == 0) {
                $this->notAttened($departure);
            } else {
                $this->attened($departure);
            }
            $this->info("departure record for employee ID  {$departure->employee->id}");
            $departure->save();
        }

    }

    private function notAttened($departure)
    {

        $notAttendedCount = Attendance::where('employee_id', $departure->employee_id)
            ->whereIn('status', [4, 5])
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        if ($notAttendedCount >= 4) {
            $departure->status = 4;

            $employeeArrivalTime = Carbon::parse($departure->employee->employee->arrival_time);
            $employeeDepartureTime = Carbon::parse($departure->employee->employee->departure_time);
            $requiredWorkHours = $employeeArrivalTime->diffInSeconds($employeeDepartureTime) / 3600;

            $deduction = Deduction::create(
                [
                    'employee_id' => $departure->employee_id,
                    'amount' => ($departure->employee->employee->salary / ($requiredWorkHours *  (Carbon::now()->daysInMonth-4) )) * ($requiredWorkHours)
                ]
            );
            $departure->deductions_id = $deduction->id;

        } else {
            $departure->status = 5;
        }

    }

    private function attened($departure)
    {
        $departure->status = 2;
    }

    private function createAttendance()
    {

        $employees = User::where('role', '<>', 0)->where('status', 1)->get();
        foreach ($employees as $employee) {
            $attendance = new Attendance();
            $attendance->employee_id = $employee->id;
            $attendance->status = 0;
            $attendance->save();
            $this->info("new attendance record for employee ID {$employee->id}");
        }
    }
}
