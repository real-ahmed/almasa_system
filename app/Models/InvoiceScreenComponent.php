<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceScreenComponent extends Model
{
    use HasFactory;
    protected $fillable = ['invoice_id', 'screen_component_id','price','quantity'];
    // Add more fields as needed

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function component()
    {
        return $this->belongsTo(ScreenComponent::class,'screen_component_id','id');
    }
}
