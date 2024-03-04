<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeRepairBonus extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'repair_type_id', 'bonus', 'bonus_type'];

}
