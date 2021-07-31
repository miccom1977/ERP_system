<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Order;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use App\Repositories\FileRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ClientRepository;
use App\Repositories\ProductRepository;
use App\Repositories\CostHomeWorkerRepository;


class OrderController extends Controller
{
    private $clientRepository;
    private $productRepository;
    private $orderRepository;
    private $costHomeWorkerRepository;
    private $fileRepository;

    public function __construct( ClientRepository $clientRepository, ProductRepository $productRepository, OrderRepository $orderRepository, CostHomeWorkerRepository  $costHomeWorkerRepository, FileRepository $fileRepository ){
        $this->clientRepository = $clientRepository;
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
        $this->costHomeWorkerRepository = $costHomeWorkerRepository;
        $this->fileRepository = $fileRepository;
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
        $order = $this->orderRepository->find($id);;
        $order->file = $this->fileRepository->find($id);
        return view('showOrder',['clients' => $this->clientRepository->getAll(), 'products' => $this->productRepository->getAll(), 'order' => $order ] );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = $this->orderRepository->find($id);;
        $order->file = $this->fileRepository->find($id);
        return view('editOrder',['clients' => $this->clientRepository->getAll(), 'products' => $this->productRepository->getAll(), 'order' => $order ] );

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
        $order =$this->orderRepository->find($id);
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
        $order = $this->orderRepository->find($id);
        $order->delete();
        return redirect('/dashboard')->with('success', 'Zlecenie usunięte.');
    }

    public function createPDF($id) {
        $order = Order::with('product')->get()->find($id);
        $dataCardboard = $this->calculateCardboard( $order->l_elem, $order->q_elem, $order->h_elem, $order->product->grammage, $order->product->designation, $order->product->cardboard_producer,( $order->quantity+($order->quantity*0.05) ),$order->l_elem_pieces, $order->q_elem_pieces );
        $order->dataCardboard = $dataCardboard;
        $order->cost_data = $this->costHomeWorkerRepository->findCost( ( $order->l_elem_pieces+$order->q_elem_pieces) );
        $order->file = $this->fileRepository->find($id);
        //return view('pdf.circulation',['order' => $order ] );
        view()->share('order', $order);
        $pdf = PDF::loadView('pdf.circulation', $order);
        $pdf->save('C://xampp/htdocs/public/files/uploaded/karta_obiegowa_'.$order->id.'_'. date_format($order->created_at, 'Y') .'.pdf');
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
                    'instruction' => 'Produkuj element Długi i Krótki, wykonaj '. ( $quantity * $piecesB ) .' uderzeń a następnie ',
                    'prodL' => $quantity * $piecesB,
                    'prodQ' => $quantity * $piecesB,
                    'consumptionA' => $quantity * $piecesB * $height,

                ],
                $mustHaveL < $mustHaveQ => [
                    'instruction' => 'Produkuj element Długi i Krótki, wykonaj '. ( $quantity * $piecesA ) .' uderzeń a następnie ',
                    'prodL' => $quantity * $piecesA,
                    'prodQ' => $quantity * $piecesA,
                    'consumptionA' => $quantity * $piecesA * $height,

                ],
                $mustHaveL == $mustHaveQ => [
                    'instruction' => 'Produkuj element Długi i Krótki, wykonaj '. ( $quantity * $piecesA ) .' uderzeń.',
                    'prodL' => $quantity * $piecesA,
                    'prodQ' => $quantity * $piecesA,
                    'consumptionA' => $quantity * $piecesA * $height,

                ]
            };
            $toDoPlus = '';
            $doK = 0;
            $doD = 0;
            $cardboardConsumptionA = $toDo['consumptionA'];
            if( $mustHaveL > $toDo['prodL'] ) // jesli nie mamy kompletów długich, dorabiamy długie
            {
                $doD = 1;
            }

            if( $mustHaveQ > $toDo['prodQ'] ) // jesli nie mamy kompltu krótkich, dorabiamy krótkie
            {
                $doK = 1;
            }

            if($doK == 1 ){
                //zmieniamy papier na taki, którym będzie mozna trzaskać dwa krókie
                $productDataKK = Product::where([
                    ['roll_width', '>', ( $widthB*2 )],
                    ['grammage', '=', $grammage],
                    ['designation', '=', $designation],
                    ['cardboard_producer', '=', $cardboard_producer]
                ])->orderBy('roll_width', 'ASC')->first();
                if($productDataKK){
                    //mamy tekturę gdzie klepiemy 2x krótkie
                    $distributionElements[1]['detail'] = '2 x KRÓTKI';
                    $distributionElements[1]['rolle_width'] = $productDataKK->roll_width;
                    $distributionElements[1]['rolle_id'] = $productDataKK->id;
                    if( $productData->roll_width !=  $productDataKK->roll_width ){
                        $toDoPlus = 'zmień rolkę na rolkę o szerokości '. $productDataKK->roll_width .' i produkuj 2 x krótkie przez '. round( ( ( ( $piecesB - $piecesA ) * $quantity )/2 ) ).' uderzeń';
                    }else{
                        $toDoPlus = ' produkuj 2 x krótkie przez '. round( ( ( ( $piecesB - $piecesA ) * $quantity )/2 ) ).' uderzeń';
                    }
                    $cardboardConsumptionB = round( ( ( ( $piecesB - $piecesA ) * $quantity )/2 ) );
                }else{
                    $productDataKKK = Product::where([
                        ['roll_width', '>', ( $widthB )],
                        ['grammage', '=', $grammage],
                        ['designation', '=', $designation],
                        ['cardboard_producer', '=', $cardboard_producer]
                    ])->orderBy('roll_width', 'ASC')->first();
                    $distributionElements[1]['detail'] = '1 x KRÓTKI';
                    $distributionElements[1]['rolle_width'] = $productDataKKK->roll_width;
                    $distributionElements[1]['rolle_id'] = $productDataKKK->id;
                    if( $productData->roll_width !=  $productDataKKK->roll_width ){
                        $toDoPlus = 'zmień rolkę na rolkę o szerokości '. $productDataKKK->roll_width .' i produkuj 1 x krótki przez '. round( ( ( $piecesB - $piecesA ) * $quantity ) ).' uderzeń';
                    }else{
                        $toDoPlus = ' produkuj 1 x krótki przez '. round( ( ( $piecesB - $piecesA ) * $quantity ) ).' uderzeń';
                    }
                    $cardboardConsumptionB = round( ( ( $piecesB - $piecesA ) * $quantity ) );
                }

                $distributionElements[1]['task_to_do'] = $toDoPlus;
                $distributionElements[1]['consumption'] = round($cardboardConsumptionB/10000);
            }

            if($doD == 1 ){
                //zmieniamy papier na taki, którym będzie mozna trzaskać dwa długie
                $productDataDD = Product::where([
                    ['roll_width', '>', ( $widthA*2 )],
                    ['grammage', '=', $grammage],
                    ['designation', '=', $designation],
                    ['cardboard_producer', '=', $cardboard_producer]
                ])->orderBy('roll_width', 'ASC')->first();
                if( $productDataDD ){
                    $distributionElements[1]['detail'] = '2 x DŁUGI';
                    $distributionElements[1]['rolle_width'] = $productDataDD->roll_width;
                    $distributionElements[1]['rolle_id'] = $productDataDD->id;

                    if( $productData->roll_width !=  $productDataDD->roll_width ){
                        $toDoPlus = ' zmień rolkę na rolkę o szerokości '. $productDataDD->roll_width .' i produkuj 2 x długie przez '. round( ( ( ( $piecesA - $piecesB ) * $quantity )/2 ) ) .' uderzeń';
                    }else{
                        $toDoPlus = ' produkuj 2 x długie przez '. round( ( ( ( $piecesA - $piecesB ) * $quantity )/2 ) ).' uderzeń';
                    }
                    $cardboardConsumptionB = round( ( ( ( $piecesA - $piecesB ) * $quantity )/2 )* $height );
                    $distributionElements[1]['task_to_do'] = $toDoPlus;
                    $distributionElements[1]['consumption'] = round($cardboardConsumptionB/10000);
                }else{
                    // klepimy po jednym długim
                    $productDataDDD = Product::where([
                        ['roll_width', '>', ( $widthA )],
                        ['grammage', '=', $grammage],
                        ['designation', '=', $designation],
                        ['cardboard_producer', '=', $cardboard_producer]
                    ])->orderBy('roll_width', 'ASC')->first();
                    if($productDataDDD){
                        // produkujemy po jednym długim
                        $distributionElements[1]['detail'] = '1 DŁUGI';
                        $distributionElements[1]['rolle_width'] = $productDataDDD->roll_width;
                        $distributionElements[1]['rolle_id'] = $productDataDDD->id;
                        $distributionElements[1]['task_to_do'] = ' produkuj element Długi przez '. (  ($piecesA-$piecesB) * $quantity ) .' uderzeń';
                        $consumptionB = round( ( ( $piecesA- $piecesB ) * $quantity ) * $height );
                    }
                    $distributionElements[1]['consumption'] = round( $consumptionB/10000 );
                }
            }
            $distributionElements[0]['task_to_do'] = $toDo['instruction'];
            $cardboardConsumptionA = $toDo['consumptionA'];
            $distributionElements[0]['consumption'] = round( ($cardboardConsumptionA+$cardboardConsumptionB)/10000 );
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
                $distributionElements[0]['task_to_do'] = ' produkuj element Długi przez '. (  $piecesA * $quantity ) .' uderzeń';
                $consumptionA = round( $piecesA * $quantity * $height );
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
                $consumptionB = round(  ($piecesB * $quantity * $height )/2 );
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
                    $consumptionB = round( $piecesB * $quantity * $height );
                }
            }
            $distributionElements[0]['consumption'] = round( ($piecesA * $quantity * $height)/10000);
            $distributionElements[1]['consumption'] =  round( $consumptionB/10000);
        }
       //echo '<pre>';
       //print_r($distributionElements);
       //echo '</pre>';
       return $distributionElements;
    }

    public function createCMR($id) {
        $pdf = PDF::loadView('cmr.cmr');
        return $pdf->download('cmr.pdf');
    }
}
