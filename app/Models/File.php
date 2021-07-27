<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    public function find($id): ?File
    {
        return File::where('order_id', '=', $id)->get();
    }
}
