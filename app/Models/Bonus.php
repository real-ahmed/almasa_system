<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    use HasFactory;

    protected $fillable = ['id','employee_id','amount'];

    // Assuming that each Bonus has many ScreenBonuses
    public function screenBonus()
    {
        return $this->hasOne(ScreenBonus::class, 'bonus_id');
    }


    public function employee()
    {
        return $this->belongsTo(User::class);
    }


    public function unlinked()
    {
        return $this->status ? false : true;
    }

}
