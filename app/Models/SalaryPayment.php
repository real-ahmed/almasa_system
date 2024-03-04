<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryPayment extends Model
{
    use HasFactory;
    protected $fillable = ['salary_amount','bonuses_amount','status'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getStatusNameAttribute()
    {

        $HtmlCode = array(
            0 => '<span class="badge badge-pill badge-secondary">' . __('قيد المعالجة') . '</span>',
            1 => '<span class="badge badge-pill badge-success">' . __('تم الدفع') . '</span>'
        );
        return $HtmlCode[$this->status];
    }
}
