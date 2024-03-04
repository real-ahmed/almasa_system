<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\SalaryPayment;
use App\Models\User;
use Illuminate\Console\Command;

class CheckSalariesAndBonuses extends Command
{



    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'salaries:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and distribute salaries and bonuses to employees.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Retrieve all employees
        $employees = User::where('role' ,'<>',0)->where('status',1)->get();

        foreach ($employees as $employee) {
            // Assuming you have a method to check if bonuses should be distributed today
            if ($employee->shouldDistributeSalaryToday()) {
                $salaryPayment = new SalaryPayment();
                $salaryPayment->salary_amount = $employee->salary;
                $salaryPayment->bonuses_amount = $employee->MonthlyBonus;
                $salaryPayment->deductions_amount = $employee->MonthlyDeduction;
                $salaryPayment->user_id = $employee->id;
                $salaryPayment->save();
                $employee->distributeBonus();
                $this->info("Bonuses and salary distributed for employee ID {$employee->id}");
            } else {
                $this->info("No distribution needed for employee ID {$employee->id}");
            }
        }
    }
}
