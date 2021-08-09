<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderPosition;
use App\Repositories\FileRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ClientRepository;
use App\Repositories\ProductRepository;
use App\Repositories\OrderPositionRepository;
use App\Repositories\CostHomeWorkerRepository;

class OrderPositionController extends Controller
{
    private $clientRepository;
    private $productRepository;
    private $orderRepository;
    private $costHomeWorkerRepository;
    private $fileRepository;
    private $orderPositionRepository;

    public function __construct( ClientRepository $clientRepository, ProductRepository $productRepository, OrderRepository $orderRepository, CostHomeWorkerRepository  $costHomeWorkerRepository, FileRepository $fileRepository, OrderPositionRepository $orderPositionRepository ){
        $this->clientRepository = $clientRepository;
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
        $this->costHomeWorkerRepository = $costHomeWorkerRepository;
        $this->fileRepository = $fileRepository;
        $this->orderPositionRepository = $orderPositionRepository;
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
    public function create( Request $request )
    {
        return view('addOrderPosition',['clients' => $this->clientRepository->getAll(), 'products' => $this->productRepository->getAll(), 'order' => $this->orderRepository->find( $request->id ), 'order_positions' => $this->orderPositionRepository->findAll( $request->id ) ] );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $orderP =new OrderPosition;
        $orderP->quantity = $request->quantity;
        $orderP->l_elem = $request->l_elem;
        $orderP->q_elem = $request->q_elem;
        $orderP->h_elem = $request->h_elem;
        $orderP->client_order_number = $request->client_order_number;
        $orderP->article_number = $request->article_number;
        $orderP->flaps_a = $request->flaps_a;
        $orderP->flaps_b = $request->flaps_b;
        $orderP->division_flapsL = $request->division_flapsL;
        $orderP->division_flapsQ = $request->division_flapsQ;
        $orderP->l_elem_pieces = $request->l_elem_pieces;
        $orderP->q_elem_pieces = $request->q_elem_pieces;
        $orderP->packaging = $request->packaging;
        $orderP->product_id = $request->product_id;
        $orderP->pallets = $request->pallets;
        $orderP->date_shipment = $request->date_shipment;
        $orderP->date_production = $request->date_production;
        $orderP->date_delivery = $request->date_delivery;
        $orderP->order_id = $request->order_id;
        $orderP->custom_order_id = $request->custom_order_id;

        $orderP->save();
        //
        return back()->with('success', 'Pozycja zamówienia zapisana.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = $this->orderRepository->find($id);
        $orderPosition = $this->orderPositionRepository->find($id);
        return view('showOrderPosition',['clients' => $this->clientRepository->getAll(), 'products' => $this->productRepository->getAll(), 'order' => $order, 'orderPosition' => $orderPosition ] );

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
        //
    }
}
