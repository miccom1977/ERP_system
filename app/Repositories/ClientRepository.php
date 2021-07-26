<?php

namespace App\Repositories;

use App\Models\Client;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class ClientRepository.
 */
class ClientRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Client::class;
    }

    public function getAll(){
        return $clients = Client::all();
    }

    /**
    * @param $id
    * @return Model
    */
    public function find($id): ?Client
    {
        return $this->model->find($id);
    }
}
