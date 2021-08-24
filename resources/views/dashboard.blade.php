<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('text.dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Success message -->
                    @if(Session::has('success'))
                        <div class="alert alert-success">
                            {{Session::get('success')}}
                        </div>
                    @endif
                    <table width="100%">
                        <tr>
                            <td>
                                Numer zlecenia
                            </td>
                            <td>
                                Numer zamówienia klienta
                            </td>
                            <td>
                                Pozycje w zamówieniu
                            </td>
                            <td>
                                Klient
                            </td>
                        </tr>
                        @foreach ( $orders as $order )
                            <tr><td><a href="/order/{{ $order->id }}">{{ $order->custom_order_id }}/{{ date_format($order->created_at, 'Y') }}</a><td>{{ $order->client_order_number }}</a></td><td>
                                @forelse ( $order->position as $singleOrderPosition)
                                    art. <a href="/orderPosition/{{ $singleOrderPosition->id }}">{{ $singleOrderPosition->article_number }}</a> | status:
                                         @if ( $singleOrderPosition->status == 0)
                                            Oczekuje<br>
                                            Produkcję rozpocząć: {{ $singleOrderPosition->date_production }}
                                        @elseif ( $singleOrderPosition->status == 1)
                                            Produkcja / sztancowanie |
                                            Składanie rozpocząć: {{ $singleOrderPosition->date_production }}
                                        @elseif ( $singleOrderPosition->status == 2)
                                            Produkcja / składanie |
                                            Spakować do: {{ $singleOrderPosition->date_shipment }}
                                        @elseif ( $singleOrderPosition->status == 3)
                                            Produkcja / spakowane |
                                            Wysłać dnia: {{ $singleOrderPosition->date_shipment }}
                                        @else
                                            Wysłane do klienta
                                        @endif
                                        <br>
                                @empty
                                    Brak pozycji dla tego zamówienia
                                @endforelse
                            </td><td>{{ $order->client->description }}</td></tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
