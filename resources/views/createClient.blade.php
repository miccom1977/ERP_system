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
                        <form action="" method="post" action="{{ route('client.store') }}">
                            @csrf
                            <table>
                                <tr>
                                    <td>
                                        <label>Nazwa klienta</label>
                                    </td>
                                    <td>
                                        <label>Miasto</label>
                                    </td>
                                    <td>
                                        <label>Kod pocztowy</label>
                                    </td>
                                    <td>
                                        <label>Kraj</label>
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
                                        <input type="text" name="city" id="city" >
                                    </td>
                                    <td>
                                        <input type="text" name="post_code" id="post_code" >
                                    </td>
                                    <td>
                                        <input type="text" name="country" id="country">
                                    </td>
                                    <td>
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
