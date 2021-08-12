<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OwnerVehicle extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'vehicle', 'description', 'tire_size_id', 'front_tire_size_id', 'body_vehicles_id', 'user_created_id', 'customer_user_id', 'current_user_id', 'wheel_size_id', 'vehicle_id', 'customer_sub_id', 'current_sub_id', 'version_car_id', 'modal_car_id', 'inside_color_id', 'outside_color_id',
    ];
}
