<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class parent_companies extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id', 'company_name','contact_person','subsidiary_id',
    ];

    

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}


