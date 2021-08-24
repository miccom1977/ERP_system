<?php

namespace App\Repositories;



use App\Models\OrderPosition;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class OrderPositionRepository.
 */
class OrderPositionRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return OrderPosition::class;
    }

    public function getAll(){
        return $orders = OrderPosition::get();
    }

    /**
    * @param $id
    * @return Model
    */
    public function find($id): ?OrderPosition
    {
        return $this->model->with('product')->with('file')->find($id);
    }

    public function findWith($client_id): ?OrderPosition
    {
        return $this->model->where('client_id', '=', $client_id)->with('file')->orderBy('custom_order_id', 'DESC')->first();
    }

    public function findAll($order_id)
    {
        return $this->model->where('order_id', '=', $order_id)->with('product')->with('file')->get();
    }

    public function findMax($id): int
    {
        return $this->model->where('order_id', '=', $id)->count();
    }
}
