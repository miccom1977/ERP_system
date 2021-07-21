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
                            <table border="1px">
                                <tr>
                                    <td>
                                        <label>Klient</label>
                                    </td>
                                    <td>
                                        <label>Sztuki</label>
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
                                    <td>
                                        <label>Pole A / B</label>
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
                                        <input type="number" class="w-24" name="quantity" id="quantity">
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
                                    <td>
                                        <input type="text" class="w-24" name="flaps_a" id="flaps_a">
                                        <input type="text" class="w-24" name="flaps_b" id="flaps_b">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Tektura</label>
                                    </td>
                                    <td>
                                        <label>Data dostawy</label>
                                    </td>
                                    <td>
                                        <label>Data wysyłki</label>
                                    </td>
                                    <td>
                                        <label>Data rozpoczęcia produkcji</label>
                                    </td>
                                    <td colspan="2">
                                        <label>Opcje</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="product_id" id="product_id">
                                            @foreach ( $products as $product )
                                                    <option value="{{ $product->id }}">{{ $product->description }} {{ $product->grammage }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="date" class="w-30" id="date_addmission" name="date_addmission" value="{{ now() }}" >
                                    </td>
                                    <td>
                                        <input type="date" class="w-30" id="date_production" name="date_production" value="{{ now() }}" >
                                    </td>
                                    <td>
                                        <input type="date" class="w-30" id="date_delivery" name="date_delivery" value="{{ now() }}" >
                                    </td>
                                    <td colspan="2">
                                        <input type="submit" name="send" value="Dodaj" class="btn btn-dark btn-block">
                                    </td>
                                </tr>
                            <!-- CROSS Site Request Forgery Protection -->
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
                                <tr><td><a href="/order/{{ $order->id }}">{{ $order->id }}/{{ date_format($order->created_at, 'Y') }}</a></td><td>{{ $order->client->description }}</td><td>{{ $order->quantity }}</td><td>{{ $order->product->description }} {{ $order->product->grammage }}</td><td>{{ $order->date_addmission }}</td><td>{{ $order->date_production }}</td><td>{{ $order->date_delivery }}</td><td><button>edytuj</button><button>usuń</button></td></tr>
                            @endforeach
                        </table>
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>
