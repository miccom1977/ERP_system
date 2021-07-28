<?php

namespace App\Repositories;

use App\Models\Product;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

//use Your Model

/**
 * Class ProductRepository.
 */
class ProductRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Product::class;
    }

    public function getAll(){
        return $clients = Product::all();
    }
    /**
    * @param $id
    * @return Model
    */
    public function find($id): ?Product
    {
        return $this->model->find($id);
    }
}
