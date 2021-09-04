<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'city',
        'post_code',
        'country',
        'street',
        'parcel_number',
        'contact_number'
    ];
}
