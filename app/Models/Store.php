<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'title',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'latitude',
        'longitude',
        'status',
        'opening_hours',
    ];

    protected $casts = [
        'opening_hours' => 'array'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
