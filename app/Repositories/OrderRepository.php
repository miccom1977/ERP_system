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
        return $orders = Order::with('position')->get();
    }

    /**
    * @param $id
    * @return Model
    */
    public function find($id): ?Order
    {
        return $this->model->find($id);
    }

    public function findWith($client_id): ?Order
    {
        return $this->model->where('client_id', '=', $client_id)->orderBy('custom_order_id', 'DESC')->first();
    }


}
