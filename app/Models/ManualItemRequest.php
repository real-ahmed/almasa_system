<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualItemRequest extends Model
{
    use HasFactory;

    protected $fillable = ['component_id','employee_id','requested_quantity'];
    public function ScreenComponent(){
        return $this->belongsTo(ScreenComponent::class,'component_id');
    }


    public function employee()
    {
        return $this->belongsTo(User::class);
    }
}
