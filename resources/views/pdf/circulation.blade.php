<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>Maro-Pack System</title>
    <style>
        body{
            font-family: "DejaVu Sans";
            letter-spacing: -0.3px;
        }
        .center{
            text-align: center;
        }
        .titleBox{
            position: absolute;
            right: 1px;
            padding:10px;
            top:0px;
            height:48px;
            width:150px;
            border:1px solid black;
        }
        .bgLogo{
            position: fixed;
            left: 20px;
            top:5px;
            width:70px;
            height:61px;
        }
        .yellow{
            background:yellow;
        }
        .green{
            background: lightgreen;
        }
        .page-break {
            page-break-after: always;
        }
        .red{
            color:red;
            font-family: "DejaVu Sans";
            font-size:16px;
        }
        .bigD{
            color:red;
            font-family: "DejaVu Sans";
            font-size:80px;
            position:absolute;
            top:300px;
            right:30px;
        }
        .signature{
            float:left;
            position:absolute;
            top:1100px;
            right:50px;
        }
    </style>
</head>
<body>
    <table width="100%" border="1"  cellspacing="0" cellpadding="0" >
        <tr>
            <td class="center" colspan="2">
                <div class="bgLogo"><img src="https://www.maro-pack.pl/assets/images/maro-packLogo.jpg"/></div>
                <h4>
                    <span>KARTA OBIEGOWA- PRODUKCJA</span>
                </h4>
                <div class="titleBox yellow"> {{ $order->parrentOrder->client->description }}<br> {{ $order->article_number }}</div>
            </td>
        </tr>
        <tr>
            <td style="text-align:right; padding-right:10px;">
                Zlecenie:
            </td>
            <td style="padding-left:10px;">
                <strong class="red">{{ $order->parrentOrder->custom_order_id }}/{{ date_format($order->parrentOrder->created_at, 'Y') }} @if( $order->max_position > 1 ) // {{  $order->order_place }} //@endif</strong>
            </td>
        </tr>
        <tr>
            <td  style="text-align:right; padding-right:10px;">
                Wykonać:
            </td>
            <td style="padding-left:10px;">
                <strong  class="red">{{ round( ( $order->quantity+($order->quantity*0.05) ) ) }} sztuk</strong>
            </td>
        </tr>
        <tr>
            <td style="text-align:right; padding-right:10px;">
                Wymiar:
            </td>
            <td style="padding-left:10px;">
                <strong class="red">{{ $order->l_elem }}</strong> x <strong  class="red">{{ $order->q_elem }}</strong> x <strong  class="red">{{ $order->h_elem }}</strong><div style="float:right; text-align:right;">POLA: <strong> {{ $order->flaps_a }}x{{ $order->flaps_b }}</strong></div>
            </td>
        </tr>
        <tr>
            <td style="text-align:right;padding-right:10px;">
                Element podłużny L:<br>
                Element poprzeczny Q:
            </td>
            <td style="padding-left:10px;">
                <strong  class="red">{{ $order->l_elem_pieces }}</strong> szt.     {{ round(( $order->l_elem_pieces*  ($order->quantity+($order->quantity*0.05) ) )) }} elementów<br>
                <strong  class="red">{{ $order->q_elem_pieces }}</strong> szt.     {{ round(( $order->q_elem_pieces*  ($order->quantity+($order->quantity*0.05) ) )) }} elementów
            </td>
        </tr>
        <tr>
            <td style="text-align:right; padding-right:10px;">
                PODZIAŁ PÓL<br>
                L: <br>
                Q: <br>
            </td>
            <td style="padding-left:10px;">
                <br>
                <strong class="red">{{ $order->division_flapsL }}</strong><br>
               <strong class="red">{{ $order->division_flapsQ }}</strong><br>
            </td>
        </tr>
        <tr>
            <td style="text-align:right;padding-right:10px;">
                ILOŚĆ SŁUPKÓW na 1000:<br>
                ILOŚĆ SŁUPKÓW na 500:<br>
            </td>
            <td style="padding-left:10px;">
                L: <strong>{{ ( $order->l_elem_pieces * 6 ) }}</strong>, Q: <strong>{{ ( $order->q_elem_pieces * 6 ) }}</strong><br>
                L: <strong>{{ ( ( $order->l_elem_pieces * 6 )/2 ) }}</strong>, Q: <strong>{{ ( ( $order->q_elem_pieces * 6 )/2 ) }}</strong><br>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:center;">
                <div class="yellow" style="border-style: dashed;width:350px;margin:0 auto; position:relative;padding:10px;text-align:center;margin-top:20px;"><strong class="red">MATERIAŁ {{ $order->product->designation }}-{{ $order->product->grammage }} g/m, {{ $order->product->cardboard_producer }}</strong></div><div class="bigD">{{ $order->product->designation }}</div><br>
                PODZIAŁ ELEMENTÓW<br>
                <table cellspacing="0" cellpadding="0" border="1" style="width: 100%;text-align:center;">
                    <tr class="yellow">
                        <td>Element</td><td>Rodzaj tektury</td><td>Gramatura</td><td>Szerokość rolki</td><td>Zadanie</td>
                    </tr>
                    @foreach ( $order->dataCardboard as $detail )
                        <tr class="yellow" style="padding:5px;">
                            <td>{!! $detail['detail'] !!}</td><td>{{ $order->product->description }} {{ $order->product->designation }}</td><td>{{ $order->product->grammage }} g/m</td><td>{{ $detail['rolle_width'] }} mm</td><td>{{ $detail['task_to_do'] }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding:15px;">
                KLIENT/FIRMA: {{ $order->parrentOrder->client->description }}
            </td>
            <td style="padding:15px;">
                TERMIN WYSYŁKI: <strong  class="red">{{ $order->date_shipment }}</strong>
            </td>
        </tr>
        <tr>
            <td style="padding:15px;">
                NR. ZAMÓWIENIA: {{ $order->parrentOrder->client_order_number }}
            </td>
            <td style="padding:15px;">
                 WIĄZAĆ PO <strong  class="red">{{ $order->packaging }}</strong> sztuk
            </td>
        </tr>
        <tr>
            <td style="padding:15px;">
                TERMIN WYKONANIA: <strong  class="red">{{ $order->date_production }}</strong>
            </td>
            <td style="padding:15px;">
                Pakować na PALETY
                @if($order->pallets == 1)
                    ZWYKŁE
                @else
                    EURO
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="2"   style=" padding:5px;">
                <div class="green" style="border-style:dashed;width:400px;margin:0 auto; position:relative;padding:20px;text-align:center;"><strong  class="red">ZAMÓWIENIE NA {{ $order->date_delivery }} !</strong></div>
            </td>
        </tr>
        <tr style="width:100px; text-align:left;">
            <td colspan="2" style="padding:10px;">
                <table style="width:100%;border:none;border-collapse:collapse;">
                    <tr>
                        <td style="border:none;width:50%;">Zamówienie na <strong>{{ $order->quantity }}</strong> sztuk</td>
                        <td style="border:1px solid black;text-align:center;">Opracował:</td>
                        <td style="border:1px solid black;text-align:center;width:35%;"> {{ Auth::user()->name }}</td>
                    </tr>
                    <tr>
                        <td style="border:none;width:50%">Nie więcej niż <strong>{{ round(( $order->quantity + ( $order->quantity*0.05) )) }}</strong> sztuk</td>
                        <td style="border:1px solid black;text-align:center;">Zatwierdził:</td>
                        <td style="border:1px solid black;width:35%;"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

    <table  cellspacing="0" cellpadding="0" border="1" style="width: 100%;text-align:center;font-size:12px;">
        <tr>
            <td  style=" padding:15px;" colspan="4">
                ARTYKUŁ: <strong>{{ $order->article_number }}</strong>
            </td>
            <td  style=" padding:15px;"  colspan="4">
                ZLECENIE: <strong> {{ ( $order->parrentOrder->custom_order_id ) }}/{{ date_format($order->parrentOrder->created_at, 'Y') }}</strong>
            </td>
        </tr>
        <tr>
            <td>
                Data/godzina
            </td>
            <td>
                Grubość materiału
            </td>
            <td>
                Długość elementu
            </td>
            <td>
                Wysokość elementu
            </td>
            <td>
                Podział pól
            </td>
            <td>
                Jakość cięć
            </td>
            <td>
                Podpis<br>
                kontrolującego
            </td>
            <td style="width:150px;">
               Uwagi
            </td>
        </tr>
        @for ($i = 1; $i < 32; $i++)
        <tr>
            <td  style="height:28px;">

            </td>
            <td style="height:28px;">

            </td>
            <td style="height:28px;">

            </td>
            <td style="height:28px;">

            </td>
            <td style="height:28px;">

            </td>
            <td style="height:28px;">

            </td>
            <td style="height:28px;">

            </td>
            <td style="height:28px;">

            </td>
        </tr>
        @endfor
    </table>


    <div class="page-break"></div>

    <table width="100%" border="1"  cellspacing="0" cellpadding="0" >
        <tr>
            <td class="center" colspan="2">
                <div class="bgLogo"><img src="https://www.maro-pack.pl/assets/images/maro-packLogo.jpg"/></div>
                <h4>
                    <span>KARTA OBIEGOWA- MAGAZYN</span>
                </h4>
                <div class="titleBox yellow"> {{ $order->parrentOrder->client->description }}<br> {{ $order->article_number }}</div>
            </td>
        </tr>
        <tr>
            <td style="text-align:right; padding-right:10px;">
                Zlecenie:
            </td>
            <td style="padding-left:10px;">
                <strong class="red">{{ $order->parrentOrder->custom_order_id }}/{{ date_format($order->parrentOrder->created_at, 'Y') }} @if( $order->max_position > 1 ) // {{  $order->order_place }} // @endif</strong>
            </td>
        </tr>
        <tr>
            <td  style="text-align:right; padding-right:10px;">
                Wykonać:
            </td>
            <td style="padding-left:10px;">
                <strong  class="red">{{ round(( $order->quantity+($order->quantity*0.05) )) }} sztuk</strong>
            </td>
        </tr>
        <tr>
            <td style="text-align:right; padding-right:10px;">
                Wymiar:
            </td>
            <td style="padding-left:10px;">
                <strong class="red">{{ $order->l_elem }}</strong> x  <strong class="red">{{ $order->q_elem }}</strong> x  <strong class="red">{{ $order->h_elem }}</strong><div style="float:right; text-align:right;">POLA: <strong> {{ $order->flaps_a }}x{{ $order->flaps_b }}</strong></div>
            </td>
        </tr>
        <tr>
            <td style="text-align:right;padding-right:10px;">
                Element podłużny L:<br>
                Element poprzeczny Q:
            </td>
            <td style="padding-left:10px;">
                <strong>{{ $order->l_elem_pieces }}</strong> szt. {{ round(( $order->l_elem_pieces*  ($order->quantity+($order->quantity*0.05) ) )) }} elementów<br>
                <strong>{{ $order->q_elem_pieces }}</strong> szt. {{ round(( $order->q_elem_pieces*  ($order->quantity+($order->quantity*0.05) ) )) }} elementów
            </td>
        </tr>
        <tr>
            <td style="text-align:right; padding-right:10px;">
                PODZIAŁ PÓL<br>
                L: <br>
                Q: <br>
            </td>
            <td style="padding-left:10px;">
                <br>
                <strong class="red">{{ $order->division_flapsL }}</strong><br>
               <strong class="red">{{ $order->division_flapsQ }}</strong><br>
            </td>
        </tr>
        <tr>
            <td style="text-align:right;padding-right:10px;">
                ILOŚĆ SŁUPKÓW na 1000:<br>
                ILOŚĆ SŁUPKÓW na 500:<br>
            </td>
            <td style="padding-left:10px;">
                L: <strong>{{ ( $order->l_elem_pieces * 6 ) }}</strong>, Q: <strong>{{ ( $order->q_elem_pieces * 6 ) }}</strong><br>
                L: <strong>{{ ( ( $order->l_elem_pieces * 6 )/2 ) }}</strong>, Q: <strong>{{ ( ( $order->q_elem_pieces * 6 )/2 ) }}</strong><br>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:center;">
                <div class="yellow" style="border-style: dashed;width:350px;margin:0 auto; position:relative;padding:10px;text-align:center;margin-top:20px;"><strong class="red">MATERIAŁ {{ $order->product->designation }}-{{ $order->product->grammage }} g/m, {{ $order->product->cardboard_producer }}</strong></div><div class="bigD">{{ $order->product->designation }}</div><br>
                ZUŻYCIE TEKTURY<br>
                <table cellspacing="0" cellpadding="0" border="1" style="width: 100%;text-align:center;">
                    <tr class="yellow" style="padding:5px">
                        <td>Rodzaj</td><td>gramatura</td><td>szerokość rolki</td><td>zużycie</td>
                    </tr>
                    @foreach ( $order->dataCardboard as $detail )
                        <tr class="yellow" style="padding:5px;">
                            <td>{{ $order->product->description }} {{ $order->product->designation }}</td><td>{{ $order->product->grammage }} g/m</td><td>{{ $detail['rolle_width'] }} mm</td><td>{{ $detail['consumption'] }} mb. rolki</td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding:15px;">
                KLIENT/FIRMA: {{ $order->parrentOrder->client->description }}
            </td>
            <td style="padding:15px;">
                TERMIN WYSYŁKI: <strong  class="red">{{ $order->date_shipment }}</strong>
            </td>
        </tr>
        <tr>
            <td style="padding:15px;">
                NR. ZAMÓWIENIA: {{ $order->parrentOrder->client_order_number }}
            </td>
            <td style="padding:15px;">
                 WIĄZAĆ PO <strong  class="red">{{ $order->packaging }}</strong> sztuk
            </td>
        </tr>
        <tr>
            <td style="padding:15px;">
                TERMIN WYKONANIA: <strong  class="red">{{ $order->date_production }}</strong>
            </td>
            <td  style="padding:15px;">
                <strong>Pakować na PALETY
                @if($order->pallets == 1)
                    ZWYKŁE
                @else
                    EURO
                @endif
                </strong>
            </td>
        </tr>
        <tr  style="width:100px; text-align:left;">
            <td colspan="2" style="padding:5px;">
                <div class="green" style="border-style:dashed;width:400px;margin:0 auto; position:relative;padding:20px;text-align:center;"><strong  class="red">ZAMÓWIENIE NA {{ $order->date_delivery }} !</strong></div>
            </td>
        </tr>
    </table><br><br>
    <table  width="100%" border="1"  cellspacing="0" cellpadding="0">
        <tr  style="width:100px; text-align:center;">
            <td style="padding:5px;" colspan="2">
                <strong>MAGAZYN - PAKOWANIE</strong>
            </td>
        </tr>
        <tr>
            <td style="padding:5px;">
                SZTUK W PACZCE:
            </td>
            <td style="padding:5px;">
                PACZEK W WARSTWIE:
            </td>
        </tr>
        <tr>
            <td style="padding:5px;">
                WARSTW W KARTONIE:
            </td>
            <td style="padding:5px;">
                KARTONÓW W ZAMÓWIENIU:
            </td>
        </tr>
    </table>


    <div class="page-break"></div>

    <table  cellspacing="0" cellpadding="0" border="1" style="width: 100%;text-align:center;">
        <tr>
            <td  style=" padding:15px;" colspan="4">
                KONTROLA KRATOWNIC
            </td>
        </tr>
        <tr>
            <td style="padding:15px;">
                Data
            </td>
            <td style="padding:15px;">
                Nazwisko
            </td>
            <td style="padding:15px;">
                Ilość w paczce
            </td>
            <td style="padding:15px;">
                Podpis<br> magazyniera
            </td>
        </tr>
        @for ($i = 1; $i < 30; $i++)
        <tr>
            <td  style="height:28px;">

            </td>
            <td style="height:28px;">

            </td>
            <td style="height:28px;">

            </td>
            <td style="height:28px;">

            </td>
        </tr>
        @endfor
    </table>


    <div class="page-break"></div>

    <table  cellspacing="0" cellpadding="0" border="1" style="width: 100%;text-align:center;">
        <tr>
            <td  style=" padding:15px;" colspan="7">
                ODBIORCA:
                <strong>{{ $order->parrentOrder->client->description }}<br>
                    {{ $order->client_order_number }}
                </strong>
            </td>
        </tr>
        <tr>
            <td  style=" padding:15px;"  colspan="7">
                ILOŚĆ ELEMENTÓW:
                <strong> {{ ( $order->q_elem_pieces + $order->l_elem_pieces) }}</strong>
            </td>
        </tr>
        <tr>
            <td  style=" padding:15px;"  colspan="7">
                CENA ZA 1000 szt. :
                <strong> {{ $order->cost_data->cost }} złotych</strong>
            </td>
        </tr>
        <tr>
            <td>
                Odbiorca
            </td>
            <td>
                Ilość pobrana
            </td>
            <td>
                Imię i Nazwisko
            </td>
            <td>
                Ilość zwrócona
            </td>
            <td>
                Data zwrotu
            </td>
            <td>
                Wydał
            </td>
            <td>
                Odebrał
            </td>
        </tr>
        @for ($i = 1; $i < 27; $i++)
        <tr>
            <td  style="height:28px;">

            </td>
            <td style="height:28px;">

            </td>
            <td style="height:28px;">

            </td>
            <td style="height:28px;">

            </td>
            <td style="height:28px;">

            </td>
            <td style="height:28px;">

            </td>
            <td style="height:28px;">

            </td>
        </tr>
        @endfor
    </table>
</body>
</html>
