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
                                Klient
                            </td>
                            <td>
                                Numer zlecenia
                            </td>
                            <td>
                                Numer zamówienia klienta
                            </td>
                            <td>
                                Pozycje w zamówieniu
                            </td>
                        </tr>
                        @foreach ( $orders as $order )
                            <tr><td>{{ $order->client->description }}</td><td><a href="/order/{{ $order->id }}">{{ $order->custom_order_id }}/{{ date_format($order->created_at, 'Y') }}</a><td>{{ $order->client_order_number }}</a></td><td>
                                @forelse ( $order->orderPositions as $singleOrderPosition)
                                    art. <a href="/orderPosition/{{ $singleOrderPosition->id }}">{{ $singleOrderPosition->article_number }}</a> | status:
                                        @if ( $singleOrderPosition->status == 0)
                                            Oczekuje |
                                            Produkcję rozpocząć: <strong @if($singleOrderPosition->date_production < now()->subDays(1)->format('Y-m-d') ) class="alert" @endif > {{ $singleOrderPosition->date_production }}</strong>
                                        @elseif ( $singleOrderPosition->status == 1)
                                            Produkcja / sztancowanie |
                                            Składanie rozpocząć: <strong @if($singleOrderPosition->date_production < now()->subDays(1)->format('Y-m-d') ) class="alert" @endif > {{ $singleOrderPosition->date_production }}</strong>
                                        @elseif ( $singleOrderPosition->status == 2)
                                            Produkcja / składanie |
                                            Spakować do: <strong @if($singleOrderPosition->date_shipment < now()->subDays(1)->format('Y-m-d') ) class="alert" @endif > {{ $singleOrderPosition->date_shipment }}</strong>
                                        @elseif ( $singleOrderPosition->status == 3)
                                            Produkcja / spakowane |
                                            Wysłać dnia: <strong @if($singleOrderPosition->date_shipment < now()->subDays(1)->format('Y-m-d') ) class="alert" @endif > {{ $singleOrderPosition->date_shipment }}</strong>
                                        @else
                                            Wysłane do klienta
                                        @endif
                                        <br>
                                @empty
                                    Brak pozycji dla tego zamówienia
                                @endforelse
                            </td></tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
