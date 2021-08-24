<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPosition extends Model
{
    use HasFactory;

    public function product(){
        return $this->belongsTo('App\Models\Product');
    }

    public function order(){
        return $this->belongsTo('App\Models\Order');
    }

    public function file(){
        return $this->hasOne('App\Models\File');
    }
}
