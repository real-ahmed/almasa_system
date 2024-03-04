<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreenBonus extends Model
{
    use HasFactory;

    public function screen()
    {
        return $this->belongsTo(Screen::class, 'screen_id');
    }

    public function bonus()
    {
        return $this->belongsTo(Bonus::class, 'bonus_id');
    }


}
