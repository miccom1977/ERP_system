<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'roll_width',
        'grammage',
        'designation',
        'count',
        'cardboard_producer',
    ];

    public function client(){
        return $this->belongsTo('App\Models\Client');
    }

    public function cardboard()
    {
        return $this->belongsTo('App\Models\Cardboard');
    }

    public function orderPosition()
    {
        return $this->belongsTo('App\Models\OrderPosition');
    }
}
