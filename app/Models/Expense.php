<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    public function receptionist()
    {
        return $this->belongsTo(User::class);
    }


    public function employee()
    {
        return $this->belongsTo(User::class);
    }


    public function getdepartmentNameAttribute(){
        $departments = array(
            1 => 'الاستقبال',
            2 => 'الصيانة',
            3 => 'المخزن',

        );
        return $departments[$this->department];
    }
}
