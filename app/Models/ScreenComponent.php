<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreenComponent extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'code', 'category_id', 'subcategory_id', 'selling_price', 'brand_id', 'auto_request_quantity','storage_partition_id'];

    public static function requestedItems()
    {
        $items = self::all(); // Fetch all records from the database

        // Filter the items using PHP after retrieving them from the database
        $requestedItems = $items->filter(function ($item) {
            return ($item->InstockQuantity <= $item->auto_request_quantity && $item->auto_request_quantity != null) || $item->manualItemRequest()->exists();
        });

        return $requestedItems;
    }

    public function repairs()
    {
        return $this->belongsToMany(Repair::class, 'repair_screen_component');
    }

    public function getInstockQuantityAttribute()
    {
        return $this->purchaseitem()->sum('instock_quantity');
    }

    public function purchaseitem()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function getRequestedQuantityAttribute()
    {
        return $this->manualItemRequest()->exists() ? $this->manualItemRequest->requested_quantity : $this->auto_request_quantity;
    }

    public function manualItemRequest()
    {
        return $this->hasOne(ManualItemRequest::class, 'component_id');
    }

    public function unlinked()
    {
        return $this->purchaseitem()->doesntExist();
    }
}
