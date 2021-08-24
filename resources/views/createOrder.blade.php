<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('text.add_new_order') }}
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
                        @if (Auth::user()->role->id ==  1 )
                            <form method="post" action="{{ route('order.store') }}">
                                @csrf
                                <table width="100%">
                                    <tr>
                                        <td>
                                            <label>Klient</label><br>
                                            <select name="client_id" id="client_id">
                                                @foreach ( $clients as $client )
                                                        <option value="{{ $client->id }}">{{ $client->description }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <label>Numer zamówienia</label><br>
                                            <input type="text" class="w-28" name="client_order_number" id="client_order_number">
                                        </td>
                                        <td><input type="submit" name="send" value="Dodaj zamówienie" class="btn btn-dark btn-block"></td>
                                    </tr>
                                </table>
                            </form>
                        @endif
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>
