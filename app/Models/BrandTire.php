<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandTire extends Model
{
    use HasFactory;

    protected $fillable = [
        'brands',
    ];
}
