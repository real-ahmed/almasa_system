<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairDeliver extends Model
{
    use HasFactory;

    public function repair(){
        return $this->belongsTo(Repair::class);
    }


    public function setTotal(){
        $this->total_amount = $this->repair->screens->whereNotIn('status', [4, 5])->sum('repair_amount');
    }


    public function getisPaidAttribute(){
        return $this->total_amount - ( $this->received_amount + $this->repair->paid ) == 0;
    }
}
