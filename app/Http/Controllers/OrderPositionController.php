<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\OrderPosition;
use App\Repositories\FileRepository;
use App\Http\Requests\OrderPositionRequest;
use App\Repositories\OrderPositionRepository;

class OrderPositionController extends Controller
{
    private $fileRepository;
    private $orderPositionRepository;

    public function __construct( FileRepository $fileRepository, OrderPositionRepository $orderPositionRepository ){
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
        return view('createOrder',['clients' => Client::All(), 'products' => Product::All(), 'orders' => Order::All() ] );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request )
    {
        return view('addOrderPosition',['clients' => Client::All(), 'products' => Product::All(), 'order' => Order::find( $request->id ) ] );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\OrderPositionRequest  $request
     * @return \Illuminate\Http\Response
     */

     public function store(OrderPositionRequest $request )

    {
        $orderP = OrderPosition::create($request->all());
        $orderP->order_place = ( $this->orderPositionRepository->findMax($request->order_id) + 1 );
        $orderP->save();
        $file = $this->fileRepository->find( $request->article_number );
        $fileInfo = 'Przejdż do edycji artykułu i dodaj rysunek!';
        if($file){
            $fileInfo = 'Rysunek mamy już w bazie';
        }

        return back()->with('success', 'Pozycja zamówienia zapisana.'. $fileInfo );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $orderPosition
     * @return \Illuminate\Http\Response
     */
    public function show(OrderPosition $orderPosition)
    {
        return view('showOrderPosition',['clients' => Client::All(), 'products' => Product::All(), 'order' => Order::find($orderPosition->order_id), 'orderPosition' => $orderPosition ] );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $orderPosition
     * @return \Illuminate\Http\Response
     */
    public function edit(OrderPosition $orderPosition)
    {
        return view('editOrderPosition',['clients' => Client::All(), 'products' => Product::All(), 'orderPosition' => $orderPosition, 'order' => Order::find($orderPosition->order_id) ] );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\OrderPositionRequest  $request
     * @param  int  $orderPosition
     * @return \Illuminate\Http\Response
     */
    public function update(OrderPositionRequest $request, OrderPosition $orderPosition)
    {
        dd($request->all());
        $orderPosition->update($request->all());
        return back()->with('success', 'Zmiany zapisane.');
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
