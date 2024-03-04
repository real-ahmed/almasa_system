<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePurchaseItemHistory extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_id','purchase_item_id','quantity'];
    public function purchaseitem(){
        return $this->belongsTo(PurchaseItem::class,'purchase_item_id');
    }
}
