<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\OrderPosition;
use Barryvdh\DomPDF\Facade as PDF;
use App\Repositories\OrderPositionRepository;
use App\Repositories\CostHomeWorkerRepository;

class OrderPositionService {

    private $costHomeWorkerRepository;
    private $orderPositionRepository;

    public function __construct( CostHomeWorkerRepository  $costHomeWorkerRepository, OrderPositionRepository $orderPositionRepository ){

        $this->costHomeWorkerRepository = $costHomeWorkerRepository;
        $this->orderPositionRepository = $orderPositionRepository;
    }

    public function calculateCardboard(OrderPosition $orderPosition )
    {
        $distributionElements = [];
        $mustHaveL = $orderPosition->quantity * $orderPosition->piecesA;
        $mustHaveQ = $orderPosition->quantity * $orderPosition->piecesB;
        $cardboardConsumptionA = $cardboardConsumptionB = 0;

        $productData = Product::where([
            ['roll_width', '>', ( $orderPosition->widthA+$orderPosition->widthB )],
            ['grammage', '=', $orderPosition->product->grammage],
            ['designation', '=', $orderPosition->product->designation],
            ['cardboard_producer', '=', $orderPosition->product->cardboard_producer]
        ])->orderBy('roll_width', 'ASC')->first();
        if( $productData ){
            $distributionElements[0]['detail'] = '1 DŁUGI<br> 1 KRÓTKI';
            $distributionElements[0]['rolle_width'] = $productData->roll_width;
            $distributionElements[0]['rolle_id'] = $productData->id;

            // obliczamy ile uderzeń maszyny należy wykonać
            $toDo = match(true){
                $mustHaveL > $mustHaveQ  => [
                    'instruction' => 'Produkuj element Długi i Krótki, wykonaj '. round(( $orderPosition->quantity * $orderPosition->piecesB )) .' uderzeń a następnie ',
                    'prodL' => round($orderPosition->quantity * $orderPosition->piecesB),
                    'prodQ' => round($orderPosition->quantity * $orderPosition->piecesB),
                    'consumptionA' => round( ( $orderPosition->quantity * $orderPosition->piecesB * $orderPosition->height)/1000 ),

                ],
                $mustHaveL < $mustHaveQ => [
                    'instruction' => 'Produkuj element Długi i Krótki, wykonaj '. round(( $orderPosition->quantity * $orderPosition->piecesA )) .' uderzeń a następnie ',
                    'prodL' => round($orderPosition->quantity * $orderPosition->piecesA),
                    'prodQ' => round($orderPosition->quantity * $orderPosition->piecesA),
                    'consumptionA' => round( ( $orderPosition->quantity * $orderPosition->piecesA * $orderPosition->height)/1000 ),

                ],
                $mustHaveL == $mustHaveQ => [
                    'instruction' => 'Produkuj element Długi i Krótki, wykonaj '. round(( $orderPosition->quantity * $orderPosition->piecesA )) .' uderzeń.',
                    'prodL' => round($orderPosition->quantity * $orderPosition->piecesA),
                    'prodQ' => round($orderPosition->quantity * $orderPosition->piecesA),
                    'consumptionA' => round( ( $orderPosition->quantity * $orderPosition->piecesA * $orderPosition->height)/1000 ),

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
                    ['roll_width', '>', ( $orderPosition->widthB*2 )],
                    ['grammage', '=', $orderPosition->product->grammage],
                    ['designation', '=', $orderPosition->product->designation],
                    ['cardboard_producer', '=', $orderPosition->product->cardboard_producer]
                ])->orderBy('roll_width', 'ASC')->first();
                if($productDataKK){
                    //mamy tekturę gdzie klepiemy 2x krótkie
                    $distributionElements[1]['detail'] = '2 x KRÓTKI';
                    $distributionElements[1]['rolle_width'] = $productDataKK->roll_width;
                    $distributionElements[1]['rolle_id'] = $productDataKK->id;
                    if( $productData->roll_width !=  $productDataKK->roll_width ){
                        $toDoPlus = 'zmień rolkę na rolkę o szerokości '. $productDataKK->roll_width .' i produkuj 2 x krótkie przez '. round( ( ( ( $orderPosition->piecesB - $orderPosition->piecesA ) * $orderPosition->quantity )/2 ) ).' uderzeń';
                    }else{
                        $toDoPlus = ' produkuj 2 x krótkie przez '. round( ( ( ( $orderPosition->piecesB - $orderPosition->piecesA ) * $orderPosition->quantity )/2 ) ).' uderzeń';
                    }
                    $cardboardConsumptionB = round( ( ( ( ( $orderPosition->piecesB - $orderPosition->piecesA ) * $orderPosition->quantity )/2 )* $orderPosition->height)/1000 );
                }else{
                    $productDataKKK = Product::where([
                        ['roll_width', '>', ( $orderPosition->widthB )],
                        ['grammage', '=', $orderPosition->grammage],
                        ['designation', '=', $orderPosition->designation],
                        ['cardboard_producer', '=', $orderPosition->cardboard_producer]
                    ])->orderBy('roll_width', 'ASC')->first();
                    $distributionElements[1]['detail'] = '1 x KRÓTKI';
                    $distributionElements[1]['rolle_width'] = $productDataKKK->roll_width;
                    $distributionElements[1]['rolle_id'] = $productDataKKK->id;
                    if( $productData->roll_width !=  $productDataKKK->roll_width ){
                        $toDoPlus = 'zmień rolkę na rolkę o szerokości '. $productDataKKK->roll_width .' i produkuj 1 x krótki przez '. round( ( ( $orderPosition->piecesB - $orderPosition->piecesA ) * $orderPosition->quantity ) ).' uderzeń';
                    }else{
                        $toDoPlus = ' produkuj 1 x krótki przez '. round( ( ( $orderPosition->piecesB - $orderPosition->piecesA ) * $orderPosition->quantity ) ).' uderzeń';
                    }
                    $cardboardConsumptionB = round( ( ( $orderPosition->piecesB - $orderPosition->piecesA ) * $orderPosition->quantity )/1000 );
                }

                $distributionElements[1]['task_to_do'] = $toDoPlus;
                $distributionElements[1]['consumption'] = $cardboardConsumptionB;
            }

            if($doD == 1 ){
                //zmieniamy papier na taki, którym będzie mozna trzaskać dwa długie
                $productDataDD = Product::where([
                    ['roll_width', '>', ( $orderPosition->widthA*2 )],
                    ['grammage', '=', $orderPosition->product->grammage],
                    ['designation', '=', $orderPosition->product->designation],
                    ['cardboard_producer', '=', $orderPosition->product->cardboard_producer]
                ])->orderBy('roll_width', 'ASC')->first();
                if( $productDataDD ){
                    $distributionElements[1]['detail'] = '2 x DŁUGI';
                    $distributionElements[1]['rolle_width'] = $productDataDD->roll_width;
                    $distributionElements[1]['rolle_id'] = $productDataDD->id;

                    if( $productData->roll_width !=  $productDataDD->roll_width ){
                        $toDoPlus = ' zmień rolkę na rolkę o szerokości '. $productDataDD->roll_width .' i produkuj 2 x długie przez '. round( ( ( ( $orderPosition->piecesA - $orderPosition->piecesB ) * $orderPosition->quantity )/2 ) ) .' uderzeń';
                    }else{
                        $toDoPlus = ' produkuj 2 x długie przez '. round( ( ( ( $orderPosition->piecesA - $orderPosition->piecesB ) * $orderPosition->quantity )/2 ) ).' uderzeń';
                    }
                    $cardboardConsumptionB = round( ( ( ( ( $orderPosition->piecesA - $orderPosition->piecesB ) * $orderPosition->quantity )/2 )* $orderPosition->height)/1000 );
                    $distributionElements[1]['task_to_do'] = $toDoPlus;
                    $distributionElements[1]['consumption'] = $cardboardConsumptionB;
                }else{
                    // klepimy po jednym długim
                    $productDataDDD = Product::where([
                        ['roll_width', '>', ( $orderPosition->widthA )],
                        ['grammage', '=', $orderPosition->product->grammage],
                        ['designation', '=', $orderPosition->product->designation],
                        ['cardboard_producer', '=', $orderPosition->product->cardboard_producer]
                    ])->orderBy('roll_width', 'ASC')->first();
                    if($productDataDDD){
                        // produkujemy po jednym długim
                        $distributionElements[1]['detail'] = '1 DŁUGI';
                        $distributionElements[1]['rolle_width'] = $productDataDDD->roll_width;
                        $distributionElements[1]['rolle_id'] = $productDataDDD->id;
                        $distributionElements[1]['task_to_do'] = ' produkuj element Długi przez '. round((  ($orderPosition->piecesA-$orderPosition->piecesB) * $orderPosition->quantity )) .' uderzeń';
                        $consumptionB = round( ( ( ( $orderPosition->piecesA - $orderPosition->piecesB ) * $orderPosition->quantity ) * $orderPosition->height)/1000 );
                    }
                    $distributionElements[1]['consumption'] = $consumptionB;
                }
            }
            $distributionElements[0]['task_to_do'] = $toDo['instruction'];
            $cardboardConsumptionA = $toDo['consumptionA'];
            $distributionElements[0]['consumption'] = round( $cardboardConsumptionA+$cardboardConsumptionB );
        }else{
            //echo 'szukamy rolki na której wybijemy długi';
            $productDataD = Product::where([
                ['roll_width', '>', $orderPosition->widthA ],
                ['grammage', '=', $orderPosition->product->grammage],
                ['designation', '=', $orderPosition->product->designation],
                ['cardboard_producer', '=', $orderPosition->product->cardboard_producer]
            ])->orderBy('roll_width', 'ASC')->first();

            if($productDataD){
                //echo 'mamy rolkę na której tłuczemy długi';
                $distributionElements[0]['detail'] = '1 DŁUGI';
                $distributionElements[0]['rolle_width'] = $productDataD->roll_width;
                $distributionElements[0]['rolle_id'] = $productDataD->id;
                $distributionElements[0]['task_to_do'] = ' produkuj element Długi przez '. round((  $orderPosition->piecesA * $orderPosition->quantity )) .' uderzeń';
                $consumptionA = round( ( $orderPosition->piecesA * $orderPosition->quantity * $orderPosition->height )/1000 );
            }else{
                //echo 'nie mamy rolki na której wybijemy długi!';
            }
            //echo 'szukamy rolki która wybije nam dwa krótkie';
            $productDataKK = Product::where([
                ['roll_width', '>', ($orderPosition->widthB*2) ],
                ['grammage', '=', $orderPosition->product->grammage],
                ['designation', '=', $orderPosition->product->designation],
                ['cardboard_producer', '=', $orderPosition->product->cardboard_producer]
            ])->orderBy('roll_width', 'ASC')->first();
            if($productDataKK){
                //echo 'mamy rolkę na której tłuczemy dwa krótkie';
                $distributionElements[1]['detail'] = '2 x KRÓTKI';
                $distributionElements[1]['rolle_width'] = $productDataKK->roll_width;
                $distributionElements[1]['rolle_id'] = $productDataKK->id;
                $distributionElements[1]['task_to_do'] = 'Produkuj dwa Krótkie przez '. round((  ($orderPosition->piecesB * $orderPosition->quantity ) /2 )) .' uderzeń';
                $consumptionB = round( ( ($orderPosition->piecesB * $orderPosition->quantity * $orderPosition->height )/2 )/1000 );
            }else{
               // echo 'Szukamy rolki, która wytnie nam krótki';
                $productDataK = Product::where([
                    ['roll_width', '>', $orderPosition->widthB ],
                    ['grammage', '=', $orderPosition->product->grammage],
                    ['designation', '=', $orderPosition->product->designation],
                    ['cardboard_producer', '=', $orderPosition->product->cardboard_producer]
                ])->orderBy('roll_width', 'ASC')->first();

                if($productDataK){
                    //echo 'mamy rolkę na której tłuczemy krótki';
                    $distributionElements[1]['detail'] = '1 KRÓTKI';
                    $distributionElements[1]['rolle_width'] = $productDataK->roll_width;
                    $distributionElements[1]['rolle_id'] = $productDataK->id;
                    $distributionElements[1]['task_to_do'] = 'Produkuj element Krótki na rolce o szerokości '. $productDataK->roll_width .' przez '. round(( $orderPosition->piecesB * $orderPosition->quantity )) .' uderzeń';
                    $consumptionB = round( ( $orderPosition->piecesB * $orderPosition->quantity * $orderPosition->height )/1000 );
                }
            }
            $distributionElements[0]['consumption'] = round( ( $orderPosition->piecesA * $orderPosition->quantity * $orderPosition->height )/1000 );
            $distributionElements[1]['consumption'] =  round( $consumptionB );
        }
       //echo '<pre>';
       //print_r($distributionElements);
       //echo '</pre>';
       return $distributionElements;
    }


    public function createPDF($orderPosition) {
        //dd($orderPosition);
        $orderPosition = OrderPosition::with('product')->get()->find($orderPosition);
        $orderPosition->dataCardboard =$this->calculateCardboard($orderPosition);
        $orderPosition->cost_data = $this->costHomeWorkerRepository->findCost( ( $orderPosition->l_elem_pieces+$orderPosition->q_elem_pieces) );
        $orderPosition->max_position = $this->orderPositionRepository->findMax($orderPosition->order_id);
        $orderPosition->parrentOrder = Order::find($orderPosition->order_id);
        view()->share('order', $orderPosition);
        $pdf = PDF::loadView('pdf.circulation', $orderPosition);
        $pdf->save('C://xampp/htdocs/public/files/uploaded/karta_obiegowa_'.$orderPosition->parrentOrder->custom_order_id.'_'.$orderPosition->order_place.'_'. date_format($orderPosition->created_at, 'Y') .'.pdf');
        return $pdf->download('karta_obiegowa_'.$orderPosition->parrentOrder->custom_order_id.'_'.$orderPosition->order_place.'_'. date_format($orderPosition->created_at, 'Y') .'.pdf');
    }

    public function createCMR($id) {
        $pdf = PDF::loadView('cmr.cmr', ['order' => $this->orderRepository->find($id)]);
        return $pdf->download('cmr_'.$id.'.pdf');
    }


    public function editStatus(Request $request) {
        $order = $this->orderPositionRepository->find($request->oneDetail);
        $order->status = $request->twoDetail;
        $order->save();
        return 'Status zlecenia zaktualizowano';
    }


    public function getAll()
    {
        return view('dashboard',['orders' => Order::All() ] );
    }

}
