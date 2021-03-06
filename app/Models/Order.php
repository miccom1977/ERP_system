<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['client_order_number', 'custom_order_id', 'client_id'];

    public function product(){
        return $this->belongsTo('App\Models\Product');
    }

    public function client(){
        return $this->belongsTo('App\Models\Client');
    }

    public function cost(){
        return $this->belongsTo('App\Models\CostHomeWorker');
    }

    public function file(){
        return $this->belongsTo('App\Models\File');
    }

    public function orderPositions(){
        return $this->hasMany('App\Models\OrderPosition');
    }
}

