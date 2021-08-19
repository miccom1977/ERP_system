<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use App\Repositories\OrderRepository;

class AddressController extends Controller
{
    private $orderRepository;

    public function __construct( OrderRepository $orderRepository ){
        $this->orderRepository = $orderRepository;

    }


    public function addNewAddress(Request $request)
    {
        $this->validate($request, [
            'street' => 'required',
            'post_code' => 'required',
            'parcel_number' => 'required',
            'country' => 'required',
        ]);

        $address = new Address;


        $address->street = $request->street;
        $address->post_code = $request->post_code;
        $address->parcel_number = $request->parcel_number;
        $address->country = $request->country;
        $address->client_id = $request->client_id;
        $address->save();
        //dd($address);
        /*
        $order =$this->orderRepository->find($request->order_id);
        $order->new_address = 1;
        $order->save();
        echo 'Nowy adres dostawy tego zlecenia dodano';
        */
    }
}
