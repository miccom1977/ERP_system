@if (Auth::user()->role->id < 3 )
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('text.edit_product') }} <strong> {{ $product->description }} {{ $product->designation }}</strong>
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
                        <form method="post" action="{{ route('product.update', $product->id ) }}">
                            @method('PUT')
                            @csrf
                            <table width="100%">
                                <tr>
                                    <td>
                                        Opis
                                    </td>
                                    <td>
                                        Szerokość rolki
                                    </td>
                                    <td>
                                        Gramatura
                                    </td>
                                    <td>
                                        Rodzaj
                                    </td>
                                    <td>
                                        Producent
                                    </td>
                                    <td>
                                        Stan magazynu
                                    </td>
                                    <td>
                                        Opcje
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="text" name="description" id="description" value="{{ $product->description }}">
                                    </td>
                                    <td>
                                        <input type="number" class="w-20" name="grammage" id="grammage" value="{{ $product->grammage }}">
                                    </td>
                                    <td>
                                        <input type="number" class="w-20" name="roll_width" id="roll_width" value="{{ $product->roll_width }}">
                                    </td>
                                    <td>
                                        <select name="designation" class="w-22" id="designation">
                                            <option value="PP" {{ $product->designation == "PP" ? 'selected' : '' }}>PP</option>
                                            <option value="GK/MGK" {{ $product->designation == "GK/MGK" ? 'selected' : '' }}>GK/MGK</option>
                                            <option value="PET" {{ $product->designation == "PET" ? 'selected' : '' }}>PET</option>
                                            <option value="FOOD" {{ $product->designation == "FOOD" ? 'selected' : '' }}>FOOD</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="cardboard_producer" id="cardboard_producer">
                                            <option value="Maro-Pack" {{ $product->cardboard_producer == "Maro-Pack" ? 'selected' : '' }}>Maro-Pack</option>
                                            <option value="Jade-Pack" {{ $product->cardboard_producer == "Jade-Pack" ? 'selected' : '' }}>Jade-Pack</option>
                                            <option value="Albertin" {{ $product->cardboard_producer == "Albertin" ? 'selected' : '' }}>Albertin</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="w-17" name="count" id="count" value="{{ $product->count }}">
                                    </td>
                                    <td><input type="submit" name="send" value="Zapisz zmiany" class="btn btn-dark btn-block"></td>
                                </tr>
                        </form>
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
