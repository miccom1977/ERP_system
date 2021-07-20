<?php

namespace App\Repositories;

use App\Models\Client;
//use Your Model

/**
 * Class ClientRepository.
 */
class ClientRepository
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
}
