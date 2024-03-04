<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;


    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }


    public function warehouseEmployee()
    {
        return $this->belongsTo(User::class);
    }


    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function unlinked()
    {
        // Check if any PurchaseItem has quantity different from stock_quantity
        $unlinkedItems = $this->items()->whereRaw('quantity != instock_quantity')->exists();

        // Return true if there are unlinked items, false otherwise
        return !$unlinkedItems;
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
