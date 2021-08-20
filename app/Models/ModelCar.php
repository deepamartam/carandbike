<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelCar extends Model
{
    use HasFactory;

    protected $fillable = [
        'model','product_category_id','brand_id',
    ];
}
