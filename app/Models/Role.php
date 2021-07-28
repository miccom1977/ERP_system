<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $quarded  = [];

    public function client(){
        return $this->belongsToMany(Permission::class);
    }
}
