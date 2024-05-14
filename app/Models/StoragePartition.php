<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoragePartition extends Model
{
    use HasFactory;
    protected $fillable = ['name'];


    public function screenComponent(){
        return $this->hasMany(ScreenComponent::class);
    }
    public function unlinked(){
        return !(
            $this->screenComponent()->exists()
        );
    }
}
