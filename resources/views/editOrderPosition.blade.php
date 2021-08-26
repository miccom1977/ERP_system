@if (Auth::user()->role->id ==  1 )
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('text.save_new_order') }} nr. {{ $order->id }}/{{ date_format($order->created_at, 'Y') }}, produkt nr. {{ $orderPosition->article_number }}
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
                        <form method="POST" action="{{ route('orderPosition.update', $orderPosition->id ) }}">
                            @method('PUT')
                            @csrf
                            <table width="100%" style="border:none;">
                                <tr>
                                    <td>
                                        <label>Klient</label>
                                    </td>
                                    <td>
                                        <label>Sztuki</label>
                                    </td>
                                    <td>

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
                                                    <option value="{{ $client->id }}" {{ $orderPosition->client_id == $client->id ? 'selected' : '' }}>{{ $client->description }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="w-28" name="quantity" id="quantity" value="{{ $orderPosition->quantity }}">
                                    </td>
                                    <td>

                                    </td>
                                    <td>
                                        <input type="text" class="w-28" name="article_number" id="article_number" value="{{ $orderPosition->article_number }}">
                                    </td>
                                    <td>
                                        <input type="number" class="w-24" name="l_elem_pieces" id="l_elem_pieces" value="{{ $orderPosition->l_elem_pieces }}">
                                    </td>
                                    <td>
                                        <input type="number" class="w-24" name="q_elem_pieces" id="q_elem_pieces" value="{{ $orderPosition->q_elem_pieces }}">
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
                                                    <option value="{{ $product->id }}" {{ $orderPosition->product->id == $product->id ? 'selected' : '' }}>{{ $product->grammage }} {{ $product->designation }} / {{ $product->cardboard_producer }} </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="w-24" name="l_elem" id="l_elem" list="defaultNumbersL" value="{{ $orderPosition->l_elem }}">
                                        <datalist id="defaultNumbersL">
                                            <option value="378">
                                            <option value="591">
                                        </datalist>
                                    </td>
                                    <td>
                                        <input type="number" class="w-24" name="q_elem" id="q_elem" list="defaultNumbersQ" value="{{ $orderPosition->q_elem }}">
                                        <datalist id="defaultNumbersQ">
                                            <option value="378">
                                            <option value="591">
                                        </datalist>
                                    </td>
                                    <td>
                                        <input type="number" class="w-24" name="h_elem" id="h_elem" list="defaultNumbersH" value="{{ $orderPosition->h_elem }}">
                                        <datalist id="defaultNumbersH">
                                            <option value="378">
                                            <option value="591">
                                        </datalist>
                                    </td>
                                    <td colspan="2">
                                        <input type="text" class="w-14" name="flaps_a" id="flaps_a"  value="{{ $orderPosition->flaps_a }}"> x <input type="text" class="w-14" name="flaps_b" id="flaps_b"  value="{{ $orderPosition->flaps_b }}">
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
                                        <input type="date" class="w-18" id="date_delivery" name="date_delivery" value="{{ $orderPosition->date_delivery }}">
                                    </td>
                                    <td>
                                        <input type="date" class="w-18" id="date_shipment" name="date_shipment" value="{{ $orderPosition->date_shipment }}">
                                    </td>
                                    <td>
                                        <input type="date" class="w-18" id="date_production" name="date_production" value="{{ $orderPosition->date_production }}">
                                    </td>
                                    <td  colspan="3">
                                        L:<input type="text" class="w-30" name="division_flapsL" id="division_flapsL" value="{{ $orderPosition->division_flapsL }}"><br>
                                        Q:<input type="text" class="w-30" name="division_flapsQ" id="division_flapsQ" value="{{ $orderPosition->division_flapsQ }}">
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
                                        <option value="1" {{ $orderPosition->pallets == 1 ? 'selected' : '' }}>ZWYKŁE</option>
                                        <option value="2" {{ $orderPosition->pallets == 2 ? 'selected' : '' }}>EURO</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="w-30" name="packaging" id="packaging" value="{{ $orderPosition->packaging }}"> szt.
                                </td>
                                <td colspan="2">
                                    <input type="submit" name="send" value="Zapisz zmiany" class="btn btn-dark btn-block">
                                </form>
                                </td>
                                <td colspan="3">
                                    @if ( !isset($orderPosition->file->path) )
                                        <form method="POST" enctype="multipart/form-data" id="upload-file" action="{{ url('store', $orderPosition->id ) }}" >
                                            @csrf
                                            @method('POST')
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <input type="file" name="file" placeholder="Choose file" id="file">
                                                        <input type="hidden" name="order_position_id" value="{{ $orderPosition->id }}">
                                                        <input type="hidden" name="article_number" value="{{ $orderPosition->article_number }}">
                                                        @error('file')
                                                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-primary" id="submit" formaction="{{ url('store/file') }}" value="file">Zapisz zdjęcie</button>
                                                </div>
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
@else
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nie masz uprawnień aby przebywać na tej stronie!
        </h2>
    </x-slot>
</x-app-layout>
@endif
