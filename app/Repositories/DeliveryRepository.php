<?php

namespace App\Repositories;

use App\Models\Delivery;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class DeliveryRepository.
 */
class DeliveryRepository extends BaseRepository
{
   /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Delivery::class;
    }

    public function getAll(){
        return $orders = Delivery::get();
    }

    /**
    * @param $id
    * @return Model
    */
    public function find($id): ?Delivery
    {
        return $this->model->find($id);
    }

    public function findWith($order_id): ?Delivery
    {
        return $this->model->where('order_id', '=', $order_id)->first();
    }
}
