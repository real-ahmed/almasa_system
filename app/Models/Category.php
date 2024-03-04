<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    public function screenComponent(){
        return $this->hasMany(ScreenComponent::class);
    }


    public function unlinked(){
        return !(
            $this->subcategories()->exists() ||
            $this->screenComponent()->exists()
        );
    }
}
