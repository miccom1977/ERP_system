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

    public function calculateCardboard( OrderPosition $orderPosition )
    {
        $distributionElements = [];
        $quantityPlusBonus = round($orderPosition->quantity + ($orderPosition->quantity * 0.05 ) );
        $mustHaveL =  $quantityPlusBonus * $orderPosition->l_elem_pieces;
        $mustHaveQ =  $quantityPlusBonus * $orderPosition->q_elem_pieces;
        $cardboardConsumptionA = $cardboardConsumptionB = 0;
        $productData = $this->findRolle(( $orderPosition->l_elem+$orderPosition->q_elem ), $orderPosition->product);
        if( $productData ){
            $distributionElements[0]['detail'] = '1 DŁUGI<br> 1 KRÓTKI';
            $distributionElements[0]['rolle_width'] = $productData->roll_width;
            $distributionElements[0]['rolle_id'] = $productData->id;

            // obliczamy ile uderzeń maszyny należy wykonać
            $toDo = match(true){
                $mustHaveL > $mustHaveQ  => [
                    'instruction' => 'Produkuj element Długi i Krótki, wykonaj '. round((  $quantityPlusBonus * $orderPosition->q_elem_pieces )) .' uderzeń a następnie ',
                    'prodL' => round( $quantityPlusBonus * $orderPosition->q_elem_pieces),
                    'prodQ' => round( $quantityPlusBonus * $orderPosition->q_elem_pieces),
                    'consumptionA' => round( (  $quantityPlusBonus * $orderPosition->q_elem_pieces * $orderPosition->h_elem)/1000 ),

                ],
                $mustHaveL < $mustHaveQ => [
                    'instruction' => 'Produkuj element Długi i Krótki, wykonaj '. round((  $quantityPlusBonus * $orderPosition->l_elem_pieces )) .' uderzeń a następnie ',
                    'prodL' => round( $quantityPlusBonus * $orderPosition->l_elem_pieces),
                    'prodQ' => round( $quantityPlusBonus * $orderPosition->l_elem_pieces),
                    'consumptionA' => round( (  $quantityPlusBonus * $orderPosition->l_elem_pieces * $orderPosition->h_elem)/1000 ),

                ],
                $mustHaveL == $mustHaveQ => [
                    'instruction' => 'Produkuj element Długi i Krótki, wykonaj '. round((  $quantityPlusBonus * $orderPosition->l_elem_pieces )) .' uderzeń.',
                    'prodL' => round( $quantityPlusBonus * $orderPosition->l_elem_pieces),
                    'prodQ' => round( $quantityPlusBonus * $orderPosition->l_elem_pieces),
                    'consumptionA' => round( (  $quantityPlusBonus * $orderPosition->l_elem_pieces * $orderPosition->h_elem)/1000 ),

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
                $productDataKK = $this->findRolle(( $orderPosition->q_elem*2 ), $orderPosition->product);

                if($productDataKK){
                    //mamy tekturę gdzie klepiemy 2x krótkie
                    $distributionElements[1]['detail'] = '2 x KRÓTKI';
                    $distributionElements[1]['rolle_width'] = $productDataKK->roll_width;
                    $distributionElements[1]['rolle_id'] = $productDataKK->id;
                    if( $productData->roll_width !=  $productDataKK->roll_width ){
                        $toDoPlus = 'zmień rolkę na rolkę o szerokości '. $productDataKK->roll_width .' i produkuj 2 x krótkie przez '. round( ( ( ( $orderPosition->q_elem_pieces - $orderPosition->l_elem_pieces ) *  $quantityPlusBonus )/2 ) ).' uderzeń';
                    }else{
                        $toDoPlus = ' produkuj 2 x krótkie przez '. round( ( ( ( $orderPosition->q_elem_pieces - $orderPosition->l_elem_pieces ) *  $quantityPlusBonus )/2 ) ).' uderzeń';
                    }
                    $cardboardConsumptionB = round( ( ( ( ( $orderPosition->q_elem_pieces - $orderPosition->l_elem_pieces ) *  $quantityPlusBonus )/2 )* $orderPosition->h_elem)/1000 );
                }else{
                    $productDataKKK = $this->findRolle( $orderPosition->q_elem, $orderPosition->product);
                    $distributionElements[1]['detail'] = '1 x KRÓTKI';
                    $distributionElements[1]['rolle_width'] = $productDataKKK->roll_width;
                    $distributionElements[1]['rolle_id'] = $productDataKKK->id;
                    if( $productData->roll_width !=  $productDataKKK->roll_width ){
                        $toDoPlus = 'zmień rolkę na rolkę o szerokości '. $productDataKKK->roll_width .' i produkuj 1 x krótki przez '. round( ( ( $orderPosition->q_elem_pieces - $orderPosition->l_elem_pieces ) *  $quantityPlusBonus ) ).' uderzeń';
                    }else{
                        $toDoPlus = ' produkuj 1 x krótki przez '. round( ( ( $orderPosition->q_elem_pieces - $orderPosition->l_elem_pieces ) *  $quantityPlusBonus ) ).' uderzeń';
                    }
                    $cardboardConsumptionB = round( ( ( $orderPosition->q_elem_pieces - $orderPosition->l_elem_pieces ) *  $quantityPlusBonus )/1000 );
                }

                $distributionElements[1]['task_to_do'] = $toDoPlus;
                $distributionElements[1]['consumption'] = $cardboardConsumptionB;
            }

            if($doD == 1 ){
                //zmieniamy papier na taki, którym będzie mozna trzaskać dwa długie
                $productDataDD = $this->findRolle( ( $orderPosition->l_elem*2 ), $orderPosition->product);
                if( $productDataDD ){
                    $distributionElements[1]['detail'] = '2 x DŁUGI';
                    $distributionElements[1]['rolle_width'] = $productDataDD->roll_width;
                    $distributionElements[1]['rolle_id'] = $productDataDD->id;

                    if( $productData->roll_width !=  $productDataDD->roll_width ){
                        $toDoPlus = ' zmień rolkę na rolkę o szerokości '. $productDataDD->roll_width .' i produkuj 2 x długie przez '. round( ( ( ( $orderPosition->l_elem_pieces - $orderPosition->q_elem_pieces ) *  $quantityPlusBonus )/2 ) ) .' uderzeń';
                    }else{
                        $toDoPlus = ' produkuj 2 x długie przez '. round( ( ( ( $orderPosition->l_elem_pieces - $orderPosition->q_elem_pieces ) *  $quantityPlusBonus )/2 ) ).' uderzeń';
                    }
                    $cardboardConsumptionB = round( ( ( ( ( $orderPosition->l_elem_pieces - $orderPosition->q_elem_pieces ) *  $quantityPlusBonus )/2 )* $orderPosition->h_elem)/1000 );
                    $distributionElements[1]['task_to_do'] = $toDoPlus;
                    $distributionElements[1]['consumption'] = $cardboardConsumptionB;
                }else{
                    // klepimy po jednym długim
                    $productDataDDD = $this->findRolle( $orderPosition->l_elem, $orderPosition->product);
                    if($productDataDDD){
                        // produkujemy po jednym długim
                        $distributionElements[1]['detail'] = '1 DŁUGI';
                        $distributionElements[1]['rolle_width'] = $productDataDDD->roll_width;
                        $distributionElements[1]['rolle_id'] = $productDataDDD->id;
                        $distributionElements[1]['task_to_do'] = ' produkuj element Długi przez '. round((  ($orderPosition->l_elem_pieces-$orderPosition->q_elem_pieces) *  $quantityPlusBonus )) .' uderzeń';
                        $consumptionB = round( ( ( ( $orderPosition->l_elem_pieces - $orderPosition->q_elem_pieces ) *  $quantityPlusBonus ) * $orderPosition->h_elem)/1000 );
                    }
                    $distributionElements[1]['consumption'] = $consumptionB;
                }
            }
            $distributionElements[0]['task_to_do'] = $toDo['instruction'];
            $cardboardConsumptionA = $toDo['consumptionA'];
            $distributionElements[0]['consumption'] = round( $cardboardConsumptionA+$cardboardConsumptionB );
        }else{
            //echo 'szukamy rolki na której wybijemy długi';
            $productDataD = $this->findRolle( $orderPosition->l_elem, $orderPosition->product);

            if($productDataD){
                //echo 'mamy rolkę na której tłuczemy długi';
                $distributionElements[0]['detail'] = '1 DŁUGI';
                $distributionElements[0]['rolle_width'] = $productDataD->roll_width;
                $distributionElements[0]['rolle_id'] = $productDataD->id;
                $distributionElements[0]['task_to_do'] = ' produkuj element Długi przez '. round((  $orderPosition->l_elem_pieces *  $quantityPlusBonus )) .' uderzeń';
                $consumptionA = round( ( $orderPosition->l_elem_pieces *  $quantityPlusBonus * $orderPosition->h_elem )/1000 );
            }else{
                //echo 'nie mamy rolki na której wybijemy długi!';
            }
            //echo 'szukamy rolki która wybije nam dwa krótkie';
            $productDataKK = $this->findRolle( ($orderPosition->q_elem*2), $orderPosition->product);
            if($productDataKK){
                //echo 'mamy rolkę na której tłuczemy dwa krótkie';
                $distributionElements[1]['detail'] = '2 x KRÓTKI';
                $distributionElements[1]['rolle_width'] = $productDataKK->roll_width;
                $distributionElements[1]['rolle_id'] = $productDataKK->id;
                $distributionElements[1]['task_to_do'] = 'Produkuj dwa Krótkie przez '. round((  ($orderPosition->q_elem_pieces *  $quantityPlusBonus ) /2 )) .' uderzeń';
                $consumptionB = round( ( ($orderPosition->q_elem_pieces *  $quantityPlusBonus * $orderPosition->h_elem )/2 )/1000 );
            }else{
               // echo 'Szukamy rolki, która wytnie nam krótki';
                $productDataK = $this->findRolle( $orderPosition->q_elem, $orderPosition->product);

                if($productDataK){
                    //echo 'mamy rolkę na której tłuczemy krótki';
                    $distributionElements[1]['detail'] = '1 KRÓTKI';
                    $distributionElements[1]['rolle_width'] = $productDataK->roll_width;
                    $distributionElements[1]['rolle_id'] = $productDataK->id;
                    $distributionElements[1]['task_to_do'] = 'Produkuj element Krótki na rolce o szerokości '. $productDataK->roll_width .' przez '. round(( $orderPosition->q_elem_pieces *  $quantityPlusBonus )) .' uderzeń';
                    $consumptionB = round( ( $orderPosition->q_elem_pieces *  $quantityPlusBonus * $orderPosition->h_elem )/1000 );
                }
            }
            $distributionElements[0]['consumption'] = round( ( $orderPosition->l_elem_pieces *  $quantityPlusBonus * $orderPosition->h_elem )/1000 );
            $distributionElements[1]['consumption'] =  round( $consumptionB );
        }

       return $distributionElements;
    }


    public function createPDF($orderPosition) {
        //dd($orderPosition);
        $orderPosition = OrderPosition::with('product')->get()->find($orderPosition);
        $orderPosition->dataCardboard = $this->calculateCardboard($orderPosition);
        $orderPosition->cost_data = $this->costHomeWorkerRepository->findCost( ( $orderPosition->l_elem_pieces+$orderPosition->q_elem_pieces) );
        $orderPosition->max_position = $this->orderPositionRepository->findMax($orderPosition->order_id);
        $orderPosition->parrentOrder = Order::find($orderPosition->order_id);
        view()->share('order', $orderPosition);
        $pdf = PDF::loadView('pdf.circulation', $orderPosition);
        $pdf->save('C://xampp/htdocs/public/files/uploaded/karta_obiegowa_'.$orderPosition->parrentOrder->custom_order_id.'_'.$orderPosition->order_place.'_'. date_format($orderPosition->created_at, 'Y') .'.pdf');
        return $pdf->download('karta_obiegowa_'.$orderPosition->parrentOrder->custom_order_id.'_'.$orderPosition->order_place.'_'. date_format($orderPosition->created_at, 'Y') .'.pdf');
    }

    public function editStatus(Request $request) {
        $order = OrderPosition::find($request->oneDetail);
        $order->status = $request->twoDetail;
        $order->save();
        return 'Status zlecenia zaktualizowano';
    }


    public function getAll()
    {
        return view('dashboard',['orders' => Order::All() ] );
    }

    public function findRolle($rollWidth, Product $product ){
        $productData = Product::where([
            ['roll_width', '>', $rollWidth ],
            ['grammage', '=', $product->grammage],
            ['designation', '=', $product->designation],
            ['cardboard_producer', '=', $product->cardboard_producer]
        ])->orderBy('roll_width', 'ASC')->first();

        return $productData;
    }

}
