<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = ['name','type'];
    public function screenComponent(){
        return $this->hasMany(ScreenComponent::class);
    }
    public function screen(){
        return $this->hasMany(screen::class);
    }


    public function screenModel(){
        return $this->hasMany(screenModel::class);
    }
    public function unlinked(){
        return !(
            $this->screenComponent()->exists() && $this->screen()->exists()

        );
    }

}
