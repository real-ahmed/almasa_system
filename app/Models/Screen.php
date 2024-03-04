<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Screen extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'brand_id', 'model', 'engineer_receive_id',  'warehouse_id', 'status','serial','issue'];

    public function repairs()
    {
        return $this->belongsToMany(Repair::class, 'screen_repairs', 'screen_id', 'repair_id');
    }


    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function screenBonuse()
    {
        return $this->hasOne(ScreenBonus::class, 'screen_id');
    }

    public function engineer_receive()
    {
        return $this->belongsTo(User::class, 'engineer_receive_id', 'id');
    }

//    public function engineer_maintenance()
//    {
//        return $this->belongsTo(User::class, 'engineer_maintenance_id', 'id');
//    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function getStatusNameAttribute()
    {

        $HtmlCode = array(
            0 => '<span class="badge badge-pill badge-secondary">' . __('تم الاستلام') . '</span>',
            1 => '<span class="badge badge-pill badge-primary">' . __('تم الصيانة') . '</span>',
            2 => '<span class="badge badge-pill badge-success">' . __('تم التسبلم') . '</span>',
            3 => '<span class="badge badge-pill badge-danger">' . __('تالف') . '</span>',
            4 => '<span class="badge badge-pill badge-warning">' . __('جاهزة للبيع') . '</span>',
            5 => '<span class="badge badge-pill badge-success">' . __('تم البيع') . '</span>'
        );
        return $HtmlCode[$this->status];
    }



    public function getStatusInvoiceAttribute()
    {

        $status = array(
            0 =>  __('تم الاستلام') ,
            1 =>  __('تم الصيانة'),
            2 =>  __('تم التسبلم') ,
            3 =>  __('تالف') ,
            4 =>  __('جاهزة للبيع') ,
            5 => __('تم البيع')
        );
        return $status[$this->status];
    }

    public function unlinked()
    {

        return $this->status == 0 && $this->components()->doesntExist() && $this->services()->doesntExist();
    }

    public function components()
    {
        return $this->hasMany(RepairScreenComponent::class);
    }

    public function services()
    {
        return $this->hasMany(RepairScreenService::class);
    }


    public function buy()
    {
        return $this->hasOne(BuyScreen::class, 'screen_id');
    }


    public function sale()
    {
        return $this->hasOne(SaleScreen::class, 'screen_id');
    }
}
