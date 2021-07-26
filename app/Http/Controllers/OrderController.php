<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
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
        $order->client_order_number = $request->client_order_number;
        $order->article_number = $request->article_number;
        $order->flaps_a = $request->flaps_a;
        $order->flaps_b = $request->flaps_b;
        $order->division_flapsL = $request->division_flapsL;
        $order->division_flapsQ = $request->division_flapsQ;
        $order->l_elem_pieces = $request->l_elem_pieces;
        $order->q_elem_pieces = $request->q_elem_pieces;
        $order->packaging = $request->packaging;
        $order->product_id = $request->product_id;
        $order->client_id = $request->client_id;
        $order->pallets = $request->pallets;
        $order->date_shipment = $request->date_shipment;
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
        $dataCardboard = $this->calculateCardboard( $order->l_elem, $order->q_elem, $order->h_elem, $order->product->grammage, $order->product->designation, $order->product->cardboard_producer,( $order->quantity+$order->quantity*0.05 ),$order->l_elem_pieces, $order->q_elem_pieces );
        $order->dataCardboard = $dataCardboard;
        //return view('pdf.circulation',['order' => $order ] );

        view()->share('order', $order);
        $pdf = PDF::loadView('pdf.circulation', $order);
        //download PDF file with download method
        return $pdf->download('karta_obiegowa_'.$order->id.'_'. date_format($order->created_at, 'Y') .'.pdf');
    }

    public function calculateCardboard( $widthA, $widthB, $height, $grammage, $designation, $cardboard_producer, $quantity, $piecesA, $piecesB )
    {
        $distributionElements = [];
        $mustHaveL = $quantity * $piecesA;
        $mustHaveQ = $quantity * $piecesB;
        $cardboardConsumptionA = $cardboardConsumptionB = 0;

        $productData = Product::where([
            ['roll_width', '>', ( $widthA+$widthB )],
            ['grammage', '=', $grammage],
            ['designation', '=', $designation],
            ['cardboard_producer', '=', $cardboard_producer]
        ])->orderBy('roll_width', 'ASC')->first();
        if( $productData ){
            $distributionElements[0]['detail'] = '1 DŁUGI + 1 KRÓTKI';
            $distributionElements[0]['rolle_width'] = $productData->roll_width;
            $distributionElements[0]['rolle_id'] = $productData->id;

            // obliczamy ile uderzeń maszyny należy wykonać
            $toDo = match(true){
                $mustHaveL > $mustHaveQ  => [
                    'instruction' => 'Produkuj element Długi i Krótki, wykonaj '. ( $quantity * $piecesA ) .' uderzeń a następnie<br>',
                    'prodL' => $quantity * $piecesA,
                    'prodQ' => $quantity * $piecesA,
                    'consumptionA' => $quantity * $piecesA * $height,

                ],
                $mustHaveL < $mustHaveQ => [
                    'instruction' => 'Produkuj element Długi i Krótki, wykonaj '. ( $quantity * $piecesB ) .' uderzeń a następnie<br>',
                    'prodL' => $quantity * $piecesB,
                    'prodQ' => $quantity * $piecesB,
                    'consumptionA' => $quantity * $piecesB * $height,

                ],
                $mustHaveL == $mustHaveQ => [
                    'instruction' => 'Produkuj element Długi i Krótki, wykonaj '. ( $quantity * $piecesA ) .' uderzeń.',
                    'prodL' => $quantity * $piecesA,
                    'prodQ' => $quantity * $piecesA,
                    'consumptionA' => $quantity * $piecesA * $height,

                ],
            };
            $toDoPlus = '';
            $doK = 0;
            $doD = 0;
            $cardboardConsumptionA = $toDo['consumptionA'];
            if( $mustHaveL < $toDo['prodL'] ) // jesli naprodukowaliśmy więcej długich jak krótkich
            {
                $doK = 1;
            }

            if( $mustHaveQ < $toDo['prodQ'] ) // jesli naprodukowaliśmy więcej krótkich jak długich
            {
                $doD = 1;
            }

            if($doK == 1 ){
                //zmieniamy papier na taki, którym będzie mozna trzaskać dwa krókie
                $productDataKK = Product::where([
                    ['roll_width', '>', ( $widthB*2 )],
                    ['grammage', '=', $grammage],
                    ['designation', '=', $designation],
                    ['cardboard_producer', '=', $cardboard_producer]
                ])->orderBy('roll_width', 'ASC')->first();
                if( $productDataKK AND $productData->roll_width !=  $productDataKK->roll_width){
                    $toDoPlus = 'zmień rolkę na rolkę o szerokości '. $productDataKK->roll_width .' i produkuj 2 x krótkie przez '. round( ( ( ( $piecesB - $piecesA ) * $quantity )/2 ) ).' uderzeń';
                }else{
                    $toDoPlus = 'następnie produkuj 2 x krótkie przez '. round( ( ( ( $piecesB - $piecesA ) * $quantity )/2 ) ).' uderzeń';
                }
                $cardboardConsumptionB = round( ( ( ( $piecesB - $piecesA ) * $quantity )/2 ) );
            }
            if($doD == 1 ){
                //zmieniamy papier na taki, którym będzie mozna trzaskać dwa długie
                $productDataDD = Product::where([
                    ['roll_width', '>', ( $widthA*2 )],
                    ['grammage', '=', $grammage],
                    ['designation', '=', $designation],
                    ['cardboard_producer', '=', $cardboard_producer]
                ])->orderBy('roll_width', 'ASC')->first();
                if( $productDataDD  AND $productData->roll_width !=  $productDataDD->roll_width){
                    $toDoPlus = 'zmień rolkę na rolkę o szerokości '. $productDataDD->roll_width .' i produkuj 2 x długie przez '. round( ( ( ( $piecesA - $piecesB ) * $quantity )/2 ) ) .' uderzeń';
                }else{
                    $toDoPlus = 'następnie produkuj 2 x długie przez '. round( ( ( ( $piecesA - $piecesB ) * $quantity )/2 ) ).' uderzeń';
                }
                $cardboardConsumptionB = round( ( ( ( $piecesB - $piecesA ) * $quantity )/2 ) );
            }
            $distributionElements[0]['task_to_do'] = $toDo['instruction'].$toDoPlus;
            $cardboardConsumptionA = $toDo['consumptionA'];
            $distributionElements[0]['consumption'] = round( ($cardboardConsumptionA+$cardboardConsumptionB)/1000 );
        }else{
            //echo 'szukamy rolki na której wybijemy długi';
                $productDataD = Product::where([
                    ['roll_width', '>', $widthA ],
                    ['grammage', '=', $grammage],
                    ['designation', '=', $designation],
                    ['cardboard_producer', '=', $cardboard_producer]
                ])->orderBy('roll_width', 'ASC')->first();

                if($productDataD){
                    //echo 'mamy rolkę na której tłuczemy długi';
                    $distributionElements[0]['detail'] = '1 DŁUGI';
                    $distributionElements[0]['rolle_width'] = $productDataD->roll_width;
                    $distributionElements[0]['rolle_id'] = $productDataD->id;
                    $distributionElements[0]['task_to_do'] = 'produkuj element Długi przez '. (  $piecesA * $quantity ) .' uderzeń';
                    $consumptionA = round(  $piecesA * $quantity * $height );
                }else{
                    //echo 'nie mamy rolki na której wybijemy długi!';
                }
            //echo 'szukamy rolki która wybije nam dwa krótkie';
            $productDataKK = Product::where([
                ['roll_width', '>', ($widthB*2) ],
                ['grammage', '=', $grammage],
                ['designation', '=', $designation],
                ['cardboard_producer', '=', $cardboard_producer]
            ])->orderBy('roll_width', 'ASC')->first();
            if($productDataKK){
                //echo 'mamy rolkę na której tłuczemy dwa krótkie';
                $distributionElements[1][detail] = '1 KRÓTKI + 1 KRÓTKI';
                $distributionElements[1]['rolle_width'] = $productDataKK->roll_width;
                $distributionElements[1]['rolle_id'] = $productDataKK->id;
                $distributionElements[1]['task_to_do'] = 'Produkuj dwa Krótkie przez '. (  ($piecesB * $quantity ) /2 ) .' uderzeń';
                $consumptionB = round(  ($piecesB * $quantity * $height ) /2 );
            }else{
               // echo 'Szukamy rolki, która wytnie nam krótki';
                $productDataK = Product::where([
                    ['roll_width', '>', $widthB ],
                    ['grammage', '=', $grammage],
                    ['designation', '=', $designation],
                    ['cardboard_producer', '=', $cardboard_producer]
                ])->orderBy('roll_width', 'ASC')->first();

                if($productDataK){
                    //echo 'mamy rolkę na której tłuczemy krótki';
                    $distributionElements[1]['detail'] = '1 KRÓTKI';
                    $distributionElements[1]['rolle_width'] = $productDataK->roll_width;
                    $distributionElements[1]['rolle_id'] = $productDataK->id;
                    $distributionElements[1]['task_to_do'] = 'Produkuj element Krótki na rolce o szerokości '. $productDataK->roll_width .' przez '. ( $piecesB * $quantity ) .' uderzeń';
                    $consumptionB = round(  $piecesB * $quantity * $height );
                }
            }
            $distributionElements[0]['consumption'] = round( ($piecesA * $quantity * $height)/1000, 2 );
            $distributionElements[1]['consumption'] =  round( ($consumptionA * $consumptionB)/1000, 2 );
        }
       //echo '<pre>';
       //print_r($distributionElements);
       //echo '</pre>';
       return $distributionElements;
    }
}
