<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Client;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use App\Repositories\OrderRepository;
use App\Repositories\ClientRepository;
use App\Repositories\ProductRepository;

class OrderController extends Controller
{
    private $clientRepository;
    private $productRepository;
    private $orderRepository;

    public function __construct( ClientRepository $clientRepository, ProductRepository $productRepository, OrderRepository $orderRepository ){
        $this->clientRepository = $clientRepository;
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('createOrder',['clients' => $this->clientRepository->getAll(), 'products' => $this->productRepository->getAll(), 'orders' => $this->orderRepository->getAll() ] );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('createOrder',['clients' => $this->clientRepository->getAll(), 'products' => $this->productRepository->getAll() ] );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        //die;

        $this->validate($request, [
            'quantity' => 'required',
            'l_elem' => 'required',
            'q_elem' => 'required',
            'h_elem' => 'required'
         ]);

        //  Store data in database
        $order = new Order;
        $order->quantity = $request->quantity;
        $order->l_elem = $request->l_elem;
        $order->q_elem = $request->q_elem;
        $order->h_elem = $request->h_elem;
        $order->flaps_a = $request->flaps_a;
        $order->flaps_b = $request->flaps_b;
        $order->product_id = $request->product_id;
        $order->client_id = $request->client_id;
        $order->date_addmission = $request->date_addmission;
        $order->date_production = $request->date_production;
        $order->date_delivery = $request->date_delivery;

        $order->save();

        //
        return back()->with('success', 'Zlecenie dodane.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('showOrder',['clients' => $this->clientRepository->getAll(), 'products' => $this->productRepository->getAll(), 'order' => $this->orderRepository->find($id) ] );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = order::find($id);
        $order->delete();
        return redirect('/dashboard')->with('success', 'Zlecenie usunięte.');
    }

    public function createPDF($id) {
        $order = Order::with('product')->get()->find($id);
        view()->share('order',$order);
        $pdf = PDF::loadView('pdf.circulation', $order);

        // download PDF file with download method
        return $pdf->download('karta_obiegowa_'.$order->id.'_'. date_format($order->created_at, 'Y') .'.pdf');
    }
}
