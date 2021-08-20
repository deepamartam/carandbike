<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelCarItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_car_id',
    ];
}
