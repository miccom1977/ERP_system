<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Zamówienie nr. : ') }} {{ $order->custom_order_id }}/{{ date_format($order->created_at, 'Y') }} <a href="/print/{{$order->id}}"><button>Drukuj dokumenty</button></a>
            @if ( isset($order->file->path) )
                <a href="c://xampp/htdocs/public/{{$order->file->path}}" downolad ><button>pobierz rysunek</button></a>
                @endif
                <a href="/printCMR/{{$order->id}}"><button>Drukuj CMR</button></a>
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
                                    <a href="{{ route('orderPosition.create',[ 'id' => $order->id ] ) }}"><button>Dodaj produkt do tego zamówienia</button></a>
                                </td>
                            </tr>

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
                                @foreach ( $order_positions as $single_position )
                                    <tr><td><a href="/orderPosition/{{ $single_position->id }}}}">{{ $single_position->client_order_number }}</a></td><td>{{ $single_position->quantity }}</td><td>{{ $single_position->l_elem }} x {{ $single_position->q_elem }} x {{ $single_position->h_elem }}</td><td>
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
                                @endforeach
                            </table>
                        </table>
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>
