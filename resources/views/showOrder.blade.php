<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Zamówienie nr. : ') }} {{ $order->id }}/{{ date_format($order->created_at, 'Y') }} <a href="/print/{{$order->id}}"><button>Drukuj dokumenty</button></a>
            @if ( isset($order->file->path) )
                <a href="c://xampp/htdocs/public/{{$order->file->path}}" downolad ><button>pobierz rysunek</button></a>

            @endif

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
                        <table width="100%">
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
                                    {{  $order->client->description }}
                                </td>
                                <td>
                                    {{  $order->quantity }} szt
                                </td>
                                <td>
                                    {{  $order->l_elem }} mm
                                <td>
                                    {{  $order->q_elem }} mm
                                </td>
                                <td>
                                    {{  $order->h_elem }} mm
                                </td>
                                <td>
                                    {{  $order->flaps_a }} x {{  $order->flaps_b }}
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
                            </tr>
                            <tr>
                                <td>
                                    {{ $order->product->description }} {{ $order->product->designation }} <br> {{ $order->product->grammage }} g/m  {{ $order->product->cardboard_producer }}
                                </td>
                                <td>
                                    {{  $order->date_delivery }}
                                </td>
                                <td>
                                    {{  $order->date_shipment }}
                                </td>
                                <td>
                                    {{  $order->date_production }}
                                </td>

                                @if (Auth::user()->role->id ==  1 )
                                <td>
                                    <a href="/order/{{$order->id}}/edit"><button>edytuj</button></a>
                                </td>
                                <td>
                                    <form method="POST" action="/order/{{$order->id}}">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <div class="form-group">
                                            <input type="submit" class="danger" value="usuń">
                                        </div>
                                    </form>
                                </td>
                                @else
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                @endif

                            </tr>
                        </table>
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>
