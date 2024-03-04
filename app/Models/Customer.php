<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone','address'];

    public function repairs()
    {
        return $this->hasMany(Repair::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }


    public function screenInvoices()
    {
        return $this->hasMany(ScreenSaleInvoice::class);
    }


}
