<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairScreenService extends Model
{
    use HasFactory;
    protected $fillable = ['id','repair_type_id','screen_id','price'];

    public function screen()
    {
        return $this->belongsTo(Screen::class);
    }

    public function service()
    {
        return $this->belongsTo(RepairType::class,'repair_type_id','id');
    }

        public function engineer_maintenance()
    {
        return $this->belongsTo(User::class, 'engineer_maintenance_id', 'id');
    }

}
