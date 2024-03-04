<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'employee_id', 'amount'];


    public function employee()
    {
        return $this->belongsTo(User::class);
    }


    public function unlinked()
    {
        return $this->status ? false : true;
    }
}
