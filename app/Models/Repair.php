<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;
    protected $fillable = ['id','customer_id','receive_date','expected_delivery_date','receptionist_id','reference_number','paid'];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function screens()
    {
        return $this->belongsToMany(Screen::class, 'screen_repairs', 'repair_id', 'screen_id');
    }


    public function receptionist()
    {
        return $this->belongsTo(User::class);
    }

    public function deliver()
    {
        return $this->hasOne(RepairDeliver::class);
    }

    public function getIsDoneAttribute(){
        return $this->screens->where('status', 0)->first() == null && $this->screens()->exists() && $this->deliver()->exists();
    }


    public function unlinked(){

        return $this->screens()->doesntExist();
    }
}
