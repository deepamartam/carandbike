<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentVehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment','product_type_id','category_equipments_id',
    ];
}
