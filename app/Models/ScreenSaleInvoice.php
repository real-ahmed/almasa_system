<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreenSaleInvoice extends Model
{
    use HasFactory;

    protected $fillable = ['id','customer_id','receptionist_id','reference_number'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function receptionist()
    {
        return $this->belongsTo(User::class);
    }


    public function screens()
    {
        return $this->hasMany(SaleScreen::class,'invoice_id');
    }

}
