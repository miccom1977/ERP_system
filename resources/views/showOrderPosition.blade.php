<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Zamówienie nr. : ') }} {{ $order->id }}/{{ date_format($order->created_at, 'Y') }} <a href="/print/{{$order->id}}"><button>Drukuj dokumenty</button></a>
            @if ( isset($orderPosition->file->path) )
                <a href="c://xampp/htdocs/public/{{$orderPosition->file->path}}" downolad ><button>pobierz rysunek</button></a>
                @endif
                <a href="/printCMR/{{$orderPosition->id}}"><button>Drukuj CMR</button></a>
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
                                    <label>Tektura</label>
                                </td>
                                <td>
                                    <label>Sztuki</label>
                                </td>
                                <td>
                                    <label>Wymiar</label>
                                </td>
                                <td>
                                    <label>Pole A / B</label>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    {{  $order->client->description }}
                                </td>
                                <td>
                                    {{ $orderPosition->product->description }} {{ $orderPosition->product->designation }} <br> {{ $orderPosition->product->grammage }} g/m  {{ $orderPosition->product->cardboard_producer }}
                                </td>
                                <td>
                                    {{  $orderPosition->quantity }} szt
                                </td>
                                <td>
                                    {{  $orderPosition->l_elem }} x {{  $orderPosition->q_elem }} x {{  $orderPosition->h_elem }}
                                </td>
                                <td>
                                    {{  $orderPosition->flaps_a }} x {{  $orderPosition->flaps_b }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Data dostawy</label>
                                </td>
                                <td>
                                    <label>Data wysyłki</label>
                                </td>
                                <td>
                                    <label>Data rozpoczęcia produkcji</label>
                                </td>
                                <td colspan="3">
                                    <label>Opcje</label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{  $orderPosition->date_delivery }}
                                </td>
                                <td>
                                    {{  $orderPosition->date_shipment }}
                                </td>
                                <td>
                                    {{  $orderPosition->date_production }}
                                </td>
                                @if (Auth::user()->role->id ==  1 )
                                <td colspan="3">
                                    <a href="/orderPosition/{{$orderPosition->id}}/edit"><button style="float:left;margin:5px 10px 5px 10px;">edytuj</button></a>
                                    <form method="POST" action="/orderPosition/{{$orderPosition->id}}">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <div class="form-group">
                                            <input type="submit" style="float:right;margin:5px 10px 5px 10px;" class="danger" value="usuń">
                                        </div>
                                    </form>
                                </td>
                                @else
                                    <td colspan="3">
                                    </td>
                                @endif

                            </tr>
                        </table>
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>
