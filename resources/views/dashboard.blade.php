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
                            <tr><td><a href="/order/{{ $order->id }}}}">{{ $order->id }}/{{ date_format($order->created_at, 'Y') }}</a></td><td>{{ $order->client->description }}</td><td>{{ $order->quantity }}</td><td>{{ $order->product->description }} {{ $order->product->grammage }}</td><td>{{ $order->date_addmission }}</td><td>{{ $order->date_production }}</td><td>{{ $order->date_delivery }}</td><td><button>edytuj</button><button>usuń</button></td></tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
