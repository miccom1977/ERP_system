<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Zamówienie nr. : ') }} {{ $order->id }}/{{ date_format($order->created_at, 'Y') }}
            @if (Auth::user()->role->id == 1 )
                <a href="/print/{{ $orderPosition->id }}"><button>Drukuj dokumenty</button></a>
                @if ( isset($orderPosition->file->path) )
                    <a href="c://xampp/htdocs/public/{{$orderPosition->file->path}}" downolad ><button>pobierz rysunek</button></a>
                @endif
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
                                    <label>Klient</label><br>
                                    {{  $order->client->description }}
                                </td>
                                <td>
                                    <label>Tektura</label><br>
                                    {{ $orderPosition->product->description }} {{ $orderPosition->product->designation }} <br> {{ $orderPosition->product->grammage }} g/m  {{ $orderPosition->product->cardboard_producer }}
                                </td>
                                <td>
                                    <label>Nr. produktu</label><br>
                                    {{  $orderPosition->article_number }}
                                </td>
                                <td>
                                    <label>Sztuki</label><br>
                                    {{  $orderPosition->quantity }} szt
                                </td>
                                <td>
                                    <label>Wymiar</label><br>
                                    {{  $orderPosition->l_elem }} x {{  $orderPosition->q_elem }} x {{  $orderPosition->h_elem }}
                                </td>
                                <td>
                                    <label>Pole celi</label><br>
                                    {{  $orderPosition->flaps_a }} x {{  $orderPosition->flaps_b }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Data dostawy</label><br>
                                    {{  $orderPosition->date_delivery }}
                                </td>
                                <td>
                                    <label>Data wysyłki</label><br>
                                    {{  $orderPosition->date_shipment }}
                                </td>
                                <td>
                                    <label>Data rozpoczęcia produkcji</label><br>
                                    {{  $orderPosition->date_production }}
                                </td>
                                <td>
                                    <label>Waga<br> 1 szt. / całość</label><br>
                                    {{ round(( ( $orderPosition->l_elem * $orderPosition->h_elem * $orderPosition->product->grammage * $orderPosition->l_elem_pieces ) + ( $orderPosition->q_elem * $orderPosition->h_elem * $orderPosition->product->grammage * $orderPosition->q_elem_pieces ) )/ 1000000)  }} g / {{ round(( ( ( ( $orderPosition->l_elem * $orderPosition->h_elem * $orderPosition->product->grammage * $orderPosition->l_elem_pieces ) + ( $orderPosition->q_elem * $orderPosition->h_elem * $orderPosition->product->grammage * $orderPosition->q_elem_pieces ) )/ 1000000 ) * $orderPosition->quantity ) / 1000) }} kg
                                </td>
                                <td>
                                    <label>Elementy</label><br>
                                    L: {{ $orderPosition->l_elem_pieces }}<br>
                                    Q: {{ $orderPosition->q_elem_pieces }}
                                </td>
                                <td>
                                    <label>Status</label><br>
                                    <select name="status" id="status">
                                        <option value="0" {{ $orderPosition->status == 0 ? 'selected' : '' }}>Oczekuje</option>
                                        <option value="1" {{ $orderPosition->status == 1 ? 'selected' : '' }}>Produkcja / sztancowanie</option>
                                        <option value="2" {{ $orderPosition->status == 2 ? 'selected' : '' }}>Produkcja / składanie</option>
                                        <option value="3" {{ $orderPosition->status == 3 ? 'selected' : '' }}>Produkcja / spakowane</option>
                                        <option value="4" {{ $orderPosition->status == 4 ? 'selected' : '' }}>Wysłane do klienta</option>
                                    </select><br>
                                    @if (Auth::user()->role->id ==  1 OR Auth::user()->role->id ==  2)
                                    <button>Zmień status</button>
                                    @endif
                                </td>
                                <td colspan="2">
                                    <label>Opcje</label><br>
                                    @if (Auth::user()->role->id ==  1 )
                                        <a href="/orderPosition/{{$orderPosition->id}}/edit"><button style="float:left;margin:5px 10px 5px 10px;">edytuj</button></a>
                                        <form method="POST" action="/orderPosition/{{$orderPosition->id}}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <div class="form-group">
                                                <input type="submit" style="float:right;margin:5px 10px 5px 10px;" class="danger" value="usuń">
                                            </div>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>
