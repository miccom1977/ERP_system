<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Repositories\OrderRepository;
use App\Repositories\ClientRepository;
use App\Repositories\ProductRepository;

class ProductController extends Controller
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
        return view('createProduct',['clients' => $this->clientRepository->getAll(), 'products' => $this->productRepository->getAll(), 'orders' => $this->orderRepository->getAll() ] );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('createProduct' );
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
            'description' => 'required',
            'roll_width' => 'required',
            'grammage' => 'required',
            'type_of_cardboard' => 'required'
         ]);

        //  Store data in database
        $product = new Product;
        $product->description = $request->description;
        $product->roll_width = $request->roll_width;
        $product->grammage = $request->grammage;

        $typeOfCard = match (true) {
            $request->type_of_cardboard == 'PP' => 1,
            $request->type_of_cardboard == 'GK/MGK' => 2,
            $request->type_of_cardboard == 'PET' => 3,
            $request->type_of_cardboard == 'FOOD' => 4,
            default => 'GK/MGK',
        };

        $cardboardProducer = match (true) {
            $request->cardboard_producer == 'Jade-Pack' => 1,
            $request->cardboard_producer == 'Maro-Pack' => 2,
            default => 'Jade-Pack',
        };
        $product->type_of_cardboard = $typeOfCard;
        $product->cardboard_producer = $cardboardProducer;
        $product->save();

        //
        return back()->with('success', 'Product dodany.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
