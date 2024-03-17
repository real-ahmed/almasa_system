<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;


    public function employee()
    {
        return $this->belongsTo(User::class);
    }

    public function deduction()
    {
        return $this->belongsTo(Deduction::class,'deductions_id');
    }


    public function getStatusNameAttribute()
    {

        $HtmlCode = array(
            0 => '<span class="badge badge-pill badge-secondary">' . __('لم يسجل بعد') . '</span>',
            1 => '<span class="badge badge-pill badge-primary">' . __('في العمل') . '</span>',
            2 => '<span class="badge badge-pill badge-success">' . __('حاضر') . '</span>',
            3 => '<span class="badge badge-pill badge-warning">' . __('تاخير') . '</span>',
            4 => '<span class="badge badge-pill badge-danger">' . __('غياب') . '</span>',
            5 => '<span class="badge badge-pill badge-success">' . __('اجازة') . '</span>'
        );
        return $HtmlCode[$this->status];
    }

}
