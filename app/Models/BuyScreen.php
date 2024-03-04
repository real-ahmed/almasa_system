<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyScreen extends Model
{
    use HasFactory;


    public function screen(){
        return $this->belongsTo(Screen::class);
    }
}
