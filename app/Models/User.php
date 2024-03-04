<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function getisadminAttribute()
    {
        if ($this->role == 0) {
            return true;
        }
        return false;
    }


    public function getisReceptionistAttribute()
    {
        if ($this->role == 0 || $this->role == 2) {
            return true;
        }
        return false;
    }

    public function getisEngineerAttribute()
    {
        if ($this->role == 0 || $this->role == 1) {
            return true;
        }
        return false;
    }

    public function getisWarehouseEmployeeAttribute()
    {
        if ($this->role == 0 || $this->role == 3) {
            return true;
        }
        return false;
    }

    public function getroleNameAttribute()
    {
        $roles = array(
            0 => 'مسؤول',
            1 => "مهندس",
            2 => "موظف استقبال",
            3 => "مسؤول تخزين"
        );
        return $roles[$this->role];
    }
    public function getstatusNameAttribute(){
        $status = array(
            0 => '<span class="badge badge-pill badge-danger">' . __('موقف') . '</span>',
            1 => '<span class="badge badge-pill badge-success">' . __('فعال') . '</span>',

        );
        return $status[$this->status];
    }


    public function employee()
    {
        return $this->hasOne(Employee::class);
    }


    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function screenRepairsAsEngineerReceive()
    {
        return $this->belongsToMany(Screen::class, 'engineer_receive_id', 'id');
    }

    public function screenRepairsAsEngineerMaintenance()
    {
        return $this->belongsToMany(RepairScreenService::class, 'engineer_maintenance_id', 'id');
    }

    public function getsalarydateAttribute()
    {
        return Carbon::parse($this->employee->salary_date);
    }

    public function getsalaryAttribute()
    {
        return $this->employee->salary;
    }

    public function shouldDistributeSalaryToday()
    {
        return $this->salarydate->day == now()->day;
    }

    public function screenRepairsAsReceptionist()
    {
        return $this->belongsToMany(Repair::class);
    }


    public function Invoices()
    {
        return $this->belongsToMany(Invoice::class);
    }

    public function ManualItemRequest()
    {
        return $this->belongsToMany(ManualItemRequest::class);
    }
    public function screenInvoices()
    {
        return $this->belongsToMany(ScreenSaleInvoice::class);
    }
    public function bonuses()
    {
        return $this->hasMany(Bonus::class, 'employee_id');
    }

    public function deductions()
    {
        return $this->hasMany(Deduction::class, 'employee_id');
    }
    public function salaryPayment()
    {
        return $this->hasMany(SalaryPayment::class);
    }
    public function getMonthlyBonusAttribute()
    {
        return $this->bonuses->where('status', 0)->sum('amount');
    }

    public function getMonthlyDeductionAttribute()
    {
        return $this->deductions->where('status', 0)->sum('amount');
    }
    public function distributeBonus()
    {
        // Assuming $this->bonuses is the relationship that holds the bonuses

        // Change the status of all bonuses to 1
        $this->bonuses()->update(['status' => 1]);

        // Additional logic for distributing salary if needed

        // Save the changes
        $this->save();
    }


    public function distributeDeduction()
    {
        // Assuming $this->bonuses is the relationship that holds the bonuses

        // Change the status of all bonuses to 1
        $this->deductions()->update(['status' => 1]);

        // Additional logic for distributing salary if needed

        // Save the changes
        $this->save();
    }

//    public function getBestScreenBonusAmount($screen)
//    {
//        $amount = 0;
//        foreach ($screen->services as $service) {
//            if ($amount < $this->getScreenBonusAmount($service)) {
//                $amount = $this->getScreenBonusAmount($service);
//            }
//        }
//        return $amount;
//
//    }
    public function getScreenBonusAmount($service)
    {
        $amount = 0;
        $service = RepairScreenService::findOrFail($service->id);

        // Check if repair_bonuses exist and have the specified repair_type_id
        $repairBonus = $this->repair_bonuses->where('repair_type_id', $service->repair_type_id)->first();

        if ($repairBonus) {
            $amount = $repairBonus->bonus;
            if ($repairBonus->bonus_type == 1) {
                $amount = $service->price * ($amount / 100);
            }
        } else {
            // If no matching repair_bonus is found, use the default bonus from the service

            $amount = $service->service->default_bonus;

            if ($service->service->bonus_type == 1) {
                $amount = $service->price * ($amount / 100);
            }


        }

        return $amount;
    }
    public function repair_bonuses()
    {
        return $this->hasMany(EmployeeRepairBonus::class);
    }

}
