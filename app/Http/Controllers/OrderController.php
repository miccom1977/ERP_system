<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Client;
use App\Models\Product;
use App\Http\Requests\OrderRequest;
use App\Repositories\OrderRepository;
use App\Repositories\DeliveryRepository;


class OrderController extends Controller
{
    private $orderRepository;
    private $deliveryRepository;

    public function __construct( OrderRepository $orderRepository, DeliveryRepository $deliveryRepository  ){
        $this->orderRepository = $orderRepository;
        $this->deliveryRepository = $deliveryRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('createOrder',['clients' => Client::All(), 'products' => Product::All(), 'orders' => Order::All() ] );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('createOrder',['clients' => Client::All(), 'products' => Product::All() ] );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request)
    {
        $order = Order::create($request->all());
        $MaxOrder = $this->orderRepository->findWith($request->client_id);
        if($MaxOrder){
            $order->custom_order_id = $MaxOrder->custom_order_id + 1;
        }else{
            $order->custom_order_id = 1;
        }
        $order->save();
        return back()->with('success', 'Zlecenie dodane.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        if($order->new_address == 1){
            $address = $this->deliveryRepository->findWith($order->id);
            $order->client->street = $address->street;
            $order->client->country = $address->country;
            $order->client->city = $address->city;
            $order->client->post_code = $address->post_code;
            $order->client->parcel_number = $address->parcel_number;
        }

        return view('showOrder',['clients' =>Client::All(), 'products' =>Product::All(), 'order' => $order ] );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        return view('editOrder',['clients' => Client::All(), 'products' => Product::All(), 'order' => $order ] );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $order
     * @return \Illuminate\Http\Response
     */
    public function update(OrderRequest $request, Order $order)
    {
        $order->update($request->all());
        return back()->with('success', 'Zmiany zapisane.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect('/dashboard')->with('success', 'Zlecenie usunięte.');
    }

}
