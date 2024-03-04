<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;





    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function screens()
    {
        return $this->hasMany(Screen::class);
    }


    public function gettypeNameAttribute(){
        $types =array(
            1 => "مخزن قطع غيار",
            2 => 'مخزن شاشات'
        );
        return $types[$this->type];
    }

    public function unlinked(){

        return $this->purchases()->doesntExist() && $this->screens()->doesntExist();
    }
}
