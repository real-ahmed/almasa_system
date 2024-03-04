<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvancePayment extends Model
{
    protected $fillable = ['id','employee_id','amount','note'];
    use HasFactory;


    public function employee()
    {
        return $this->belongsTo(User::class);
    }


    public function unlinked()
    {
        return $this->status ? false : true;
    }
}
