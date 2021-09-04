<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'l_elem',
        'q_elem',
        'h_elem',
        'article_number',
        'flaps_a',
        'flaps_b',
        'division_flapsL',
        'division_flapsQ',
        'l_elem_pieces',
        'q_elem_pieces',
        'packaging',
        'product_id',
        'pallets',
        'date_shipment',
        'date_production',
        'date_delivery',
        'order_id',
        'custom_order_id',
<<<<<<< HEAD
        'status',
=======
>>>>>>> c6a660c93b0a837af0482aeccd2f807511b43258
    ];



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
