<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('text.add_new_product') }}
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
                        @if (Auth::user()->role->id ==  1 )
                            <form method="post" action="{{ route('product.store') }}">
                                @csrf
                                <table>
                                    <tr>
                                        <td>
                                            <label>Opis Tektury</label>
                                        </td>
                                        <td>
                                            <label>Gramatura</label>
                                        </td>
                                        <td>
                                            <label>Szerokość rolki</label>
                                        </td>
                                        <td>
                                            <label>Rodzaj tektury</label>
                                        </td>
                                        <td>
                                            <label>Producent</label>
                                        </td>
                                        <td>
                                            <label>Opcje</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name="description" id="description">
                                        </td>
                                        <td>
                                            <input type="number" name="grammage" id="grammage" list="defaultNumbersGramme">
                                            <datalist id="defaultNumbersGramme">
                                                <option value="600">
                                                <option value="685">
                                                <option value="700">
                                                <option value="790">
                                                <option value="800">
                                            </datalist>
                                        </td>
                                        <td>
                                            <input type="number" name="roll_width" id="roll_width" list="defaultNumbersWidth">
                                            <datalist id="defaultNumbersWidth">
                                                <option value="600">
                                                <option value="685">
                                                <option value="700">
                                                <option value="790">
                                                <option value="800">
                                            </datalist>
                                        </td>
                                        <td>
                                            <input type="text" name="designation" id="designation" list="defaultNumbersL">
                                            <datalist id="defaultNumbersL">
                                                <option value="PP">
                                                <option value="GK/MGK">
                                                <option value="PET">
                                                <option value="FOOD">
                                            </datalist>
                                        </td>
                                        <td>
                                            <input type="text" name="cardboard_producer" id="cardboard_producer" list="defaultNumbersProducer">
                                            <datalist id="defaultNumbersProducer">
                                                <option value="Maro-Pack">
                                                <option value="Jade-Pack">
                                                <option value="Albertin">
                                                <option value="DS-Smith">
                                            </datalist>
                                        </td>
                                        <td>
                                            <input type="submit" name="send" value="Dodaj" class="btn btn-dark btn-block">
                                        </td>
                                    </tr>
                                <!-- CROSS Site Request Forgery Protection -->
                            </form>
                            </table>
                            @endif
                        </div>
                    <div class="p-6 bg-white border-b border-gray-200">
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
                                   Stan magazynowy
                                </td>
                                <td>
                                    Opcje
                                </td>
                            </tr>
                            @foreach ( $products as $product )
                                <tr>
                                    <td>{{ $product->description }}</td>
                                    <td>{{ $product->roll_width }} mm</td>
                                    <td>{{ $product->grammage }} g/m</td>
                                    <td>{{ $product->designation }}</td>
                                    <td>{{ $product->cardboard_producer }}</td>
                                    <td>{{ $product->count }}</td>
                                    <td>
                                        @if (Auth::user()->role->id <3 )
                                            <a href="/product/{{$product->id}}/edit"><button>edytuj</button></a>
                                            @if (Auth::user()->role->id == 1 )
                                                <form method="POST" action="/product/{{$product->id}}">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <div class="form-group">
                                                        <input type="submit" class="danger" value="usuń">
                                                    </div>
                                                </form>
                                            @endif
                                        @else
                                            Nie masz uprawnień do edycji
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>
