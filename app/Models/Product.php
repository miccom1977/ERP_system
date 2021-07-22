<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function client(){
        return $this->belongsTo('App\Models\Client');
    }

    public function cardboard()
    {
        return $this->belongsTo('App\Models\Cardboard');
    }
}
