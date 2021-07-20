<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('text.add_new_order') }}
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
                        <form action="" method="post" action="{{ route('createCirculateDoc.store') }}">
                            @csrf
                            <table>
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
                                        <label>Tektura</label>
                                    </td>
                                    <td>
                                        <label>Opcje</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="client_id" id="client_id">
                                            @foreach ( $clients as $client )
                                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="quantity" id="quantity">
                                    </td>
                                    <td>
                                        <input type="number" class="w-24" name="l_elem" id="l_elem" list="defaultNumbersL">
                                        <datalist id="defaultNumbersL">
                                            <option value="378">
                                            <option value="591">
                                        </datalist>
                                    </td>
                                    <td>
                                        <input type="number" class="w-24" name="q_elem" id="q_elem" list="defaultNumbersQ">
                                        <datalist id="defaultNumbersQ">
                                            <option value="378">
                                            <option value="591">
                                        </datalist>
                                    </td>
                                    <td>
                                        <input type="number" class="w-24" name="h_elem" id="h_elem" list="defaultNumbersH">
                                        <datalist id="defaultNumbersH">
                                            <option value="378">
                                            <option value="591">
                                        </datalist>
                                    </td>
                                    <td>
                                        <select name="product_id" id="product_id">
                                            @foreach ( $products as $product )
                                                    <option value="{{ $product->id }}">{{ $product->description }} {{ $product->grammage }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="submit" name="send" value="Dodaj" class="btn btn-dark btn-block">
                                    </td>
                                </tr>
                            <!-- CROSS Site Request Forgery Protection -->
                        </form>
                        </table>
                    </div>

                    <div class="p-6 bg-white border-b border-gray-200">
                        @foreach ( $orders as $order )
                                <p>{{ $order->id }}: {{ $order->description }} : {{ $order->product->description }}</p>
                        @endforeach
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>
