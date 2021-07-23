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
        table{
            text-align: center;
        }
    </style>
</head>
<body>
    <table width="100%">
        <tr>
            <td>
                <h4>
                    <span>KARTA OBIEGOWA- PRODUKCJA</span>
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Zlecenie {{ $order->id }}/{{ date_format($order->created_at, 'Y') }}</strong>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Wykonać: {{ ( $order->quantity+($order->quantity*0.05) ) }} sztuk</strong>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Wymiar: {{ $order->l_elem }} x {{ $order->q_elem }} x {{ $order->h_elem }}</strong> POLA: <strong> {{ $order->flaps_a }}x{{ $order->flaps_b }}</strong>
            </td>
        </tr>
        <tr>
            <td>
                Element podłużny L: <strong>{{ $order->l_elem }}</strong> szt.  {{ ( $order->l_elem*  ($order->quantity+($order->quantity*0.05) ) ) }} elementów<br>
                Element poprzeczny Q: <strong>{{ $order->q_elem }}</strong> szt.    {{ ( $order->q_elem*  ($order->quantity+($order->quantity*0.05) ) ) }} elementów<br>
            </td>
        </tr>
        <tr>
            <td>
                PODZIAŁ PÓL<br>
                L: <strong>{{ $order->division_flapsL }}</strong><br>
                Q: <strong>{{ $order->division_flapsL }}</strong><br>
            </td>
        </tr>
        <tr>
            <td>
                ILOŚĆ SŁUPKÓW na 1000 <strong> L: {{ ( $order->l_elem * 6 ) }}</strong>, Q: {{ ( $order->q_elem * 6 ) }}<br>
                ILOŚĆ SŁUPKÓW na 500 <strong> L: {{ ( ( $order->l_elem * 6 )/2 ) }}</strong>, Q: {{ ( ( $order->q_elem * 6 )/2 ) }}<br>
            </td>
        </tr>
        <tr>
            <td>
                MATERIAŁ <strong>: {{ $order->product->designation }}-{{ $order->product->grammage }} g/m, {{ $order->product->cardboard_producer }}</strong><br>
                ROZKŁAD ELEMENTÓW<br>
                <table>
                    <tr>
                        <td>Element</td><td>Rodzaj tektury</td><td>Gramatura</td><td>Szerokość rolki</td><td>Zadanie</td>
                    </tr>
                    @foreach ( $order->dataCardboard as $detail )
                        <tr>
                            <td>{{ $detail['detail'] }}</td><td>{{ $order->product->description }}</td><td>{{ $order->product->grammage }}</td><td>{{ $detail['rolle_width'] }}</td><td>{{ $detail['task_to_do'] }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
