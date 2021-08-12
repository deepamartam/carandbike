<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bidauction extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'bid_auctions';

    protected $fillable = [
        'user_id','subsidiary_id','ad_vehicle_id','status','offer',
    ];

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}

