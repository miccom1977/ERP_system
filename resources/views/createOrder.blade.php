<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('text.add_new_order') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 ">
                        <!-- Success message -->
                        @if(Session::has('success'))
                            <div class="alert alert-success">
                                {{Session::get('success')}}
                            </div>
                        @endif
                        <form action="" method="post" action="{{ route('order.store') }}">
                            @csrf
                            <table width="100%">
                                <tr>
                                    <td>
                                        <label>Klient</label>
                                    </td>
                                    <td>
                                        <label>Sztuki</label>
                                    </td>
                                    <td>
                                        <label>Numer zamówienia</label>
                                    </td>
                                    <td>
                                        <label>Nr. artykułu</label>
                                    </td>
                                    <td>
                                        <label>Długi el. L szt.</label>
                                    </td>
                                    <td>
                                        <label>Krótki el. Q szt.</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="client_id" id="client_id">
                                            @foreach ( $clients as $client )
                                                    <option value="{{ $client->id }}">{{ $client->description }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="w-28" name="quantity" id="quantity">
                                    </td>
                                    <td>
                                        <input type="text" class="w-28" name="client_order_number" id="client_order_number">
                                    </td>
                                    <td>
                                        <input type="text" class="w-28" name="article_number" id="article_number">
                                    </td>
                                    <td>
                                        <input type="number" class="w-24" name="l_elem_pieces" id="l_elem_pieces">
                                    </td>
                                    <td>
                                        <input type="number" class="w-24" name="q_elem_pieces" id="q_elem_pieces">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6">
                                        <hr>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Tektura</label>
                                    </td>
                                    <td>
                                        <label>Wymiar L</label>
                                    </td>
                                    <td>
                                        <label>Wymiar Q</label>
                                    </td>
                                    <td>
                                        <label>Wysokość</label>
                                    </td>
                                    <td colspan="2">
                                        <label>Pole</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="product_id" id="product_id">
                                            @foreach ( $products as $product )
                                                    <option value="{{ $product->id }}">{{ $product->description }} {{ $product->grammage }} {{ $product->designation }} / {{ $product->cardboard_producer }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="w-24" name="l_elem" id="l_elem" list="defaultNumbersL">
                                        <datalist id="defaultNumbersL">
                                            <option value="378">
                                            <option value="591">
                                        </datalist>
                                    </td>
                                    <td>
                                        <input type="number" class="w-24" name="q_elem" id="q_elem" list="defaultNumbersQ">
                                        <datalist id="defaultNumbersQ">
                                            <option value="378">
                                            <option value="591">
                                        </datalist>
                                    </td>
                                    <td>
                                        <input type="number" class="w-24" name="h_elem" id="h_elem" list="defaultNumbersH">
                                        <datalist id="defaultNumbersH">
                                            <option value="378">
                                            <option value="591">
                                        </datalist>
                                    </td>
                                    <td colspan="2">
                                        <input type="text" class="w-14" name="flaps_a" id="flaps_a"> x <input type="text" class="w-14" name="flaps_b" id="flaps_b">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6">
                                        <hr>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Data dostawy do klienta</label>
                                    </td>
                                    <td>
                                        <label>Data wysyłki</label>
                                    </td>
                                    <td>
                                        <label>Data rozpoczęcia produkcji</label>
                                    </td>
                                    <td colspan="3">
                                        <label>Podział pól<br></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="date" class="w-18" id="date_delivery" name="date_delivery" value="{{ now() }}" >
                                    </td>
                                    <td>
                                        <input type="date" class="w-18" id="date_shipment" name="date_shipment" value="{{ now() }}" >
                                    </td>
                                    <td>
                                        <input type="date" class="w-18" id="date_production" name="date_production" value="{{ now() }}" >
                                    </td>
                                    <td  colspan="3">
                                        L:<input type="text" class="w-30" name="division_flapsL" id="division_flapsL"><br>
                                        Q:<input type="text" class="w-30" name="division_flapsQ" id="division_flapsQ">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6">
                                        <hr>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Rodzaj palet</label>
                                    </td>
                                    <td>
                                        <label>Wiązać po:</label>
                                    </td>
                                    <td colspan="4">
                                        opcje
                                    </td>
                                </tr>
                                <td>
                                    <select name="pallets" id="pallets">
                                        <option value="1">ZWYKŁE</option>
                                        <option value="2">EURO</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="w-30" name="packaging" id="packaging"> szt.
                                </td>
                                <td colspan="5">
                                    <input type="submit" name="send" value="Dodaj zamówienie" class="btn btn-dark btn-block">
                                </td>
                            </tr>
                        </form>
                        </table>
                    </div>

                    <div class="p-6 bg-white border-b border-gray-200">
                        <table width="100%">
                            <tr>
                                <td>
                                    Numer zamówienia
                                </td>
                                <td>
                                    Klient
                                </td>
                                <td>
                                    Ilość sztuk
                                </td>
                                <td>
                                    Tektura
                                </td>
                                <td>
                                    Data przyjęcia
                                </td>
                                <td>
                                    Data produkcji
                                </td>
                                <td>
                                    Data wysyłki
                                </td>
                                <td>
                                    Opcje
                                </td>
                            </tr>
                            @foreach ( $orders as $order )
                                <tr><td><a href="/order/{{ $order->id }}">{{ $order->id }}/{{ date_format($order->created_at, 'Y') }}</a></td><td>{{ $order->client->description }}</td><td>{{ $order->quantity }}</td><td>{{ $order->product->description }} {{ $order->product->designation }} {{ $order->product->grammage }} g/m <br>{{ $order->product->cardboard_producer }}, szer. {{ $order->product->roll_width }} mm</td><td>{{ $order->date_addmission }}</td><td>{{ $order->date_production }}</td><td>{{ $order->date_delivery }}</td><td><button>edytuj</button><button class="danger">usuń</button></td></tr>
                            @endforeach
                        </table>
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>
