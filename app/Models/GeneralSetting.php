<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;
    protected $casts = [
        'phone' => 'array',
    ];

    public function scopeSiteName($query, $pageTitle)
    {
        $pageTitle = empty($pageTitle) ? '' : ' - ' . $pageTitle;
        return $this->site_name . $pageTitle;
    }


    protected static function boot()
    {
        parent::boot();
        static::saved(function(){
            \Cache::forget('GeneralSetting');
        });
    }
}
