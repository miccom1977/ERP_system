<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    public $fillable = ['quantity', 'l_elem', 'q_elem', 'h_elem', 'client_id'];

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
}

