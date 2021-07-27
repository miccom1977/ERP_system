<?php

namespace App\Repositories;

use App\Models\File;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class FileRepository.
 */
class FileRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return File::class;
    }

    /**
    * @param $id
    * @return Model
    */
    public function find($id): ?File
    {
        return $cost = File::where('order_id','=', $id)->first();
    }
}
