<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('text.add_new_client') }}
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
                        <form method="post" action="{{ route('client.store') }}">
                            @csrf
                            <table width="100%">
                                <tr>
                                    <td>
                                        <label>Nazwa klienta</label><br>
                                        <input type="text" name="description" id="description">
                                    </td>
                                    <td>
                                        <label>Miasto</label><br>
                                        <input type="text" name="city" id="city">
                                    </td>
                                    <td>
                                        <label>Kod pocztowy</label><br>
                                        <input type="text" name="post_code" id="post_code">
                                    </td>
                                    <td>
                                        <label>Kraj</label><br>
                                        <input type="text" name="country" id="country">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Ulica</label><br>
                                        <input type="text" name="street" id="street">
                                    </td>
                                    <td>
                                        <label>Numer budynku</label><br>
                                        <input type="text" name="parcel_number" id="parcel_number" >
                                    </td>
                                    <td>
                                        <label>Numer kontaktowy</label><br>
                                        <input type="text" name="contact_number" id="contact_number" >
                                    </td>
                                    <td>
                                        <label>Opcje</label><br>
                                        <input type="submit" name="send" value="Dodaj" class="btn btn-dark btn-block">
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                    <div class="p-6 bg-white border-b border-gray-200">
                        <table width="100%">
                            <tr>
                                <td>
                                    Nazwa
                                </td>
                                <td>
                                    Miasto
                                </td>
                                <td>
                                    Kod pocztowy
                                </td>
                                <td>
                                    Kraj
                                </td>
                                <td>
                                    Opcje
                                </td>
                            </tr>
                            @foreach ( $clients as $client )
                                <tr><td>{{ $client->description }}</td><td>{{ $client->city }}</td><td>{{ $client->post_code }}</td><td>{{ $client->country }}</td><td><a href="/client/{{$client->id}}/edit"><button>edytuj</button></a><button class="danger">usuń</button></td></tr>
                            @endforeach
                        </table>
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>
