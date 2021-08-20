<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Zamówienie nr. : ') }} {{ $order->custom_order_id }}/{{ date_format($order->created_at, 'Y') }}
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
                        <table width="100%" >
                            <tr>
                                <td colspan="2">
                                    <label>Klient</label>
                                </td>
                                <td>
                                    <label>Numer zamówienia</label>
                                </td>
                                <td>
                                    <label>Wysłać na adres</label>
                                </td>
                                <td>
                                    <label>Akcja</label>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    {{  $order->client->description }}
                                </td>
                                <td>
                                    {{ $order->custom_order_id }}/{{ date_format($order->created_at, 'Y') }}
                                </td>
                                <td>
                                    @if (Auth::user()->role->id ==  1 )
                                        <input type="text" name="city" value="{{  $order->client->city }}"/>
                                        <input type="text" name="street" value="{{  $order->client->street }}"/> <input type="text" name="parcel_number" value="{{  $order->client->parcel_number }}"/><br>
                                        <input type="text" name="post_code" value="{{  $order->client->post_code }}"/> <input type="text" name="country" value="{{  $order->client->country }}"/><br>
                                        <input type="hidden" name="client_id" value="{{  $order->client->id }}"/>
                                        <input type="hidden" name="order_id" value="{{  $order->id }}"/>
                                        <button class="btn-submit">Zmień adres dostawy</button>
                                    @else
                                        {{ $order->client->street }} {{  $order->client->parcel_number }}<br>
                                        {{  $order->client->post_code }} {{  $order->client->city }}<br> {{  $order->client->country }}<br>
                                    @endif
                                </td>
                                <td>
                                    @if (Auth::user()->role->id ==  1 )
                                        <a href="{{ route('orderPosition.create',[ 'id' => $order->id, ] ) }}"><button>Dodaj produkt do tego zamówienia</button></a>
                                    @else
                                        Brak
                                    @endif
                                </td>
                            </tr>
                        </table>
                        <br><br><br>
                        Artykuły w tym zamówieniu:

                        <table width="100%">
                            <tr>
                                <td>
                                    Numer produktu
                                </td>
                                <td>
                                    Ilosć sztuk
                                </td>
                                <td>
                                    Wymiary
                                </td>
                                <td>
                                    Status
                                </td>
                            </tr>
                            @forelse( $order_positions as $single_position )
                                <tr><td><a href="/orderPosition/{{ $single_position->id }}">{{ $single_position->article_number }}</a></td><td>{{ $single_position->quantity }}</td><td>{{ $single_position->l_elem }} x {{ $single_position->q_elem }} x {{ $single_position->h_elem }}</td><td>
                                    @if ( $single_position->status == 0)
                                        Oczekuje
                                    @elseif ( $single_position->status == 1)
                                        Produkcja / sztancowanie
                                    @elseif ( $single_position->status == 2)
                                        Produkcja / składanie
                                    @elseif ( $single_position->status == 3)
                                        Produkcja / spakowane
                                    @else
                                        Wysłane do klienta
                                    @endif
                                </td></tr>
                            @empty
                                Brak pozycji dla tego zamówienia.<br>
                                <a href="{{ route('orderPosition.create',[ 'id' => $order->id, ] ) }}"><button>Dodaj produkt do tego zamówienia</button></a>
                            @endforelse
                        </table>

                    </div>
                </div>
        </div>
    </div>
</x-app-layout>
