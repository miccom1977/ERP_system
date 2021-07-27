<?php

namespace App\Repositories;

use App\Models\CostHomeWorker;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class CostHomeWorker.
 */
class CostHomeWorkerRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return CostHomeWorker::class;
    }

    public function getAll(){
        return $CostHomeWorker = CostHomeWorker::with('product')->get();
    }

    /**
    * @param $id
    * @return Model
    */
    public function findCost($id)
    {
        return $cost = CostHomeWorker::where('truss_elements','=', $id)->first();
    }
}
