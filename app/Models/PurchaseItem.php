<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = ['screen_component_id', 'quantity' . 'price'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function ScreenPurchaseItemHistory()
    {
        $this->hasMany(ScreenPurchaseItemHistory::class);
    }


    public function InvoicePurchaseItemHistory()
    {
        $this->hasMany(InvoicePurchaseItemHistory::class);
    }


    public function product()
    {
        return $this->belongsTo(ScreenComponent::class, 'screen_component_id');
    }


}
