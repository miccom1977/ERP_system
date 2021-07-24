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
        $dataCardboard = $this->calculateCardboard( $order->l_elem, $order->q_elem, $order->h_elem, $order->product->grammage, $order->product->designation, $order->product->cardboard_producer,( $order->quantity+$order->quantity*0.05 ),$order->l_elem_pieces, $order->q_elem_pieces );
        //$order->dataCardboard = $dataCardboard;
        //view()->share('order', $order);
        //$pdf = PDF::loadView('pdf.circulation', $order);
        // download PDF file with download method
        //return $pdf->download('karta_obiegowa_'.$order->id.'_'. date_format($order->created_at, 'Y') .'.pdf');
    }

    public function calculateCardboard( $widthA, $widthB, $height, $grammage, $designation, $cardboard_producer, $quantity, $piecesA, $piecesB )
    {
        /*
             Założenia
                wyszukaj kombinację papieru i elementów przynoszacą najmniej straty

                długi z krótkim
                długi z długim + krótki z krótkim
                długi z krótkim
                długi z krótkim + krótki

                szukam papieru z najmniejszą możliwą kombinacją i liczę, ile będzie straty
        */
        // wyciągam wszystkie rolki papieru o zgodnych parametrach i obliczam dla nich odpad

        $productData = Product::where([
            ['grammage', '=', $grammage],
            ['designation', '=', $designation],
            ['cardboard_producer', '=', $cardboard_producer]
        ])->get();
        $odpady = [];
        $i = 0;
        foreach ( $productData as $product ) {
            $combinationType = match(true){
                $widthA < $product->roll_width => [( $product->roll_width - $widthA ), 1, ( $height * $quantity * $piecesA ) ],
                $widthA + $widthB < $product->roll_width => [( $product->roll_width - ( $widthA + $widthB ) ), 2, ( ( $height * $quantity * $piecesA )+( $height * $quantity * $piecesB ) ) ],
                $widthA + $widthA < $product->roll_width => [( $product->roll_width - ( $widthA * 2 ) ), 3, ( ( $height * $quantity * $piecesA )/2 ) ],
                default => 0,
            };
            if( $combinationType != 0 ){
                $odpady[$i] = [
                    'cardboard_id'     => $product->id,
                    'odpad'            => $combinationType[0],
                    'width'            => $widthA,
                    'combinationType'  => $combinationType[1],
                    'roll_width'       => $product->roll_width,
                    'roll_consumption' => $combinationType[2]
                ];
            }
            $i++;
        }

        // kombinacja druga

        foreach ( $productData as $product ) {
            $combinationType = match(true){
                $widthB * 2 < $product->roll_width => [( $product->roll_width - ( $widthB * 2 ) ),4, ( ( $height * $quantity * $piecesB )/2 ) ],
                $widthB < $product->roll_width => [( $product->roll_width - $widthB ),5, ( $height * $quantity * $piecesB )],
                default => 0,
            };
            if( $combinationType != 0 ){
                $odpady[$i] = [
                    'cardboard_id'    => $product->id,
                    'odpad'           => $combinationType[0],
                    'width'           => $widthB,
                    'combinationType' => $combinationType[1],
                    'roll_width'      => $product->roll_width,
                    'roll_consumption' => $combinationType[2]
                ];
                $i++;
            }
        }

        $id = 0;
        $odpad = 500;
        $odpad2 = 500;

        $szerokoscA = $widthA;
        $szerokoscB = $widthB;
       $columns = array_column($odpady, 'odpad');
       array_multisort($columns, SORT_ASC, $odpady);
        foreach ( $odpady as $tab ) {
            if( $tab['odpad'] < $odpad AND $tab['width'] == $szerokoscA ){
                $odpad     = $tab['odpad'];
                $arrayA[$id] = [
                    'odpad' => $tab['odpad'],
                    'cardboard_id' => $tab['cardboard_id'],
                    'width' => $tab['width'],
                    'combinationType' => $tab['combinationType'],
                    'roll_width'      => $tab['roll_width'],
                    'roll_consumption' => $tab['roll_consumption'],
                    'odpad_sum'        => ( $tab['roll_consumption']*$tab['odpad'] )/1000
                ];
            }
            if($tab['odpad'] < $odpad2 AND $tab['width'] == $szerokoscB ){
                $odpad2     = $tab['odpad'];
                $arrayB[$id] = [
                    'odpad' => $tab['odpad'],
                    'cardboard_id' => $tab['cardboard_id'],
                    'width' => $tab['width'],
                    'combinationType'  => $tab['combinationType'],
                    'roll_width'       => $tab['roll_width'],
                    'roll_consumption' => $tab['roll_consumption'],
                    'odpad_sum'        => ( $tab['roll_consumption']*$tab['odpad'] )/1000

                ];
            }
            $id++;
        }

        $newArr = array_merge($arrayA, $arrayB);
        echo '<pre>';
        print_r($newArr);
        echo '</pre>';

        //die;
       /*
        $distributionElements = [];
        $productData = Product::where([
            ['roll_width', '>', ( $widthA+$widthB )],
            ['grammage', '=', $grammage],
            ['designation', '=', $designation],
            ['cardboard_producer', '=', $cardboard_producer]
        ])->orderBy('roll_width', 'ASC')->first();
        if( $productData ){
            $distributionElements[0]['detail'] = '1 DŁ + 1 KR';
            $distributionElements[0]['rolle_width'] = $productData->roll_width;
            $distributionElements[0]['rolle_id'] = $productData->id;
            // obliczamy ile uderzeń maszyny należy wykonać
            $toDo = match(true){
                ( $quantity * $piecesA ) > ( $quantity * $piecesB ) => 'Produkuj element Długi i Krótki, wykonaj '. ( $quantity * $piecesB ) .' uderzeń a następnie ',
                ( $quantity * $piecesA ) < ( $quantity * $piecesB ) => 'Produkuj element Długi i Krótki, wykonaj '. ( $quantity * $piecesA ) .' uderzeń a następnie produkuj długie '. ( ( $piecesA - $piecesB )* $quantity ) .' uderzeń',
                ( $quantity * $piecesA ) == ( $quantity * $piecesB ) => 'Produkuj element Długi i Krótki, wykonaj '. ( $quantity * $piecesA ) .' uderzeń',
            };
            $doK = 0;
            $doD = 0;
            if( ( 2*$widthB ) < $productData->roll_width AND $piecesA > $piecesB){
                $toDoPlus = 'zmień sztancę i produkuj 2 x krótkie przez '. ( ( ( $piecesA-$piecesB ) * $quantity )/2 ) .'  uderzeń';
                $doK = 1;
            }
            if( ( 2*$widthA ) < $productData->roll_width AND $piecesA > $piecesB){
                $toDoPlus = 'zmień sztancę i produkuj 2 x długie przez '. ( ( ( $piecesA-$piecesB ) * $quantity )/2 ) .'  uderzeń';
                $doD = 1;
            }
            if($doK == 0 ){
                //zmieniamy papier na taki, którym będzie mozna trzaskać dwa krókie
                $productDataKK = Product::where([
                    ['roll_width', '>', ( $widthB*2 )],
                    ['grammage', '=', $grammage],
                    ['designation', '=', $designation],
                    ['cardboard_producer', '=', $cardboard_producer]
                ])->orderBy('roll_width', 'ASC')->first();
                if( $productDataKK ){
                    $toDoPlus = 'zmień rolkę na rolkę o szerokości '. $productDataKK->roll_width .' i produkuj 2 x krótkie przez '. ( ( ( $piecesA - $piecesB ) * $quantity )/2 ) .' uderzeń';
                }
                $distributionElements[0]['task_to_do'] = $toDo.$toDoPlus;
            }
            if($doD == 0 ){
                //zmieniamy papier na taki, którym będzie mozna trzaskać dwa krókie
                $productDataKK = Product::where([
                    ['roll_width', '>', ( $widthA*2 )],
                    ['grammage', '=', $grammage],
                    ['designation', '=', $designation],
                    ['cardboard_producer', '=', $cardboard_producer]
                ])->orderBy('roll_width', 'ASC')->first();
                if( $productDataKK ){
                    $toDoPlus = 'zmień rolkę na rolkę o szerokości '. $productDataKK->roll_width .' i produkuj 2 x długie przez '. ( ( ( $piecesA - $piecesB ) * $quantity )/2 ) .' uderzeń';
                }
                $distributionElements[0]['task_to_do'] = $toDo.$toDoPlus;
            }
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
                    $distributionElements[0]['detail'] = '1 DŁ';
                    $distributionElements[0]['rolle_width'] = $productDataD->roll_width;
                    $distributionElements[0]['rolle_id'] = $productDataD->id;
                    $distributionElements[0]['task_to_do'] = 'produkuj element Długi przez '. (  $piecesA * $quantity ) .' uderzeń';
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
                $distributionElements[1][detail] = '1 KR + 1 KR';
                $distributionElements[1]['rolle_width'] = $productDataKK->roll_width;
                $distributionElements[1]['rolle_id'] = $productDataKK->id;
                $distributionElements[1]['task_to_do'] = 'Produkuj dwa Krótkie przez '. (  ($piecesB * $quantity ) /2 ) .' uderzeń';
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
                    $distributionElements[1]['detail'] = '1 KR';
                    $distributionElements[1]['rolle_width'] = $productDataK->roll_width;
                    $distributionElements[1]['rolle_id'] = $productDataK->id;
                    $distributionElements[1]['task_to_do'] = 'Produkuj element Krótki na rolce o szerokości '. $productDataK->roll_width .' przez '. ( $piecesB * $quantity ) .' uderzeń';
                }
            }
        }
         */
       // return $distributionElements;
    }
}
