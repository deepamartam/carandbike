<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_user_id',
        'house_no',
        'street',
        'zip_code',
        'city',
        'country_id',
        'subsidiary_id',
    ];
}
