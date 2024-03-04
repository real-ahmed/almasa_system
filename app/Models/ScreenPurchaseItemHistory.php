<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreenPurchaseItemHistory extends Model
{
    use HasFactory;
    protected $fillable = ['screen_id','purchase_item_id','quantity'];
    public function purchaseitem(){
        return $this->belongsTo(PurchaseItem::class,'purchase_item_id');
    }
}
