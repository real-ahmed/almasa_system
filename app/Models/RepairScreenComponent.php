<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairScreenComponent extends Model
{
    use HasFactory;

    protected $fillable = ['screen_id', 'screen_component_id','price','quantity'];
    // Add more fields as needed

    public function screen()
    {
        return $this->belongsTo(Screen::class);
    }

    public function component()
    {
        return $this->belongsTo(ScreenComponent::class,'screen_component_id','id');
    }
}
