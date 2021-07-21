<?php

namespace App\Repositories;

use App\Models\Order;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class OrderRepository.
 */
class OrderRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Order::class;
    }

    public function getAll(){
        return $orders = Order::with('product')->get();
    }

    /**
    * @param $id
    * @return Model
    */
    public function find($id): ?Order
    {
        return $this->model->find($id);
    }
}
