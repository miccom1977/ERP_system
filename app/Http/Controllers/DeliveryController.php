<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use App\Repositories\OrderRepository;
use App\Repositories\DeliveryRepository;

class DeliveryController extends Controller
{
    private $orderRepository;
    private $deliveryRepository;

    public function __construct( OrderRepository $orderRepository, DeliveryRepository $deliveryRepository ){
        $this->orderRepository = $orderRepository;
        $this->deliveryRepository = $deliveryRepository;

    }


    public function addNewAddress(Request $request)
    {
        $this->validate($request, [
            'street' => 'required',
            'post_code' => 'required',
            'parcel_number' => 'required',
            'country' => 'required',
            'city' => 'required',
        ]);

        $delivery = $this->deliveryRepository->findWith($request->order_id);
        if($delivery){
            $delivery->street = $request->street;
            $delivery->post_code = $request->post_code;
            $delivery->parcel_number = $request->parcel_number;
            $delivery->country = $request->country;
            $delivery->city = $request->city;
            $delivery->save();
        }else{
            $address = new Delivery;
            $address->street = $request->street;
            $address->post_code = $request->post_code;
            $address->parcel_number = $request->parcel_number;
            $address->country = $request->country;
            $address->order_id = $request->order_id;
            $address->city = $request->city;
            $address->save();
        }

        $order =$this->orderRepository->find($request->order_id);
        $order->new_address = 1;
        $order->save();
        return 'Nowy adres dostawy tego zlecenia dodano';
    }
}
