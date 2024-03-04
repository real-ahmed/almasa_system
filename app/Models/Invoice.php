<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'total_amount', 'invoice_date'];

    // Add more fields as needed

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function receptionist()
    {
        return $this->belongsTo(User::class);
    }


    public function components()
    {
        return $this->hasMany(InvoiceScreenComponent::class);
    }
}
