<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
<<<<<<< HEAD
    protected $fillable = ['client_order_number', 'custom_order_id', 'client_id'];
=======
    protected $fillable = ['quantity', 'l_elem', 'q_elem', 'h_elem', 'client_id'];
>>>>>>> c6a660c93b0a837af0482aeccd2f807511b43258

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

