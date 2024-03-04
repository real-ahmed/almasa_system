<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleScreen extends Model
{
    use HasFactory;


    protected $fillable = ['price','invoice_id','screen_id'];

    public function screen(){
        return $this->belongsTo(Screen::class);
    }

    public function ScreenSaleInvoice(){
        $this->belongsTo(ScreenSaleInvoice::class);
    }

}
