<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('text.edit_client') }} <strong>{{ $client->description }}</strong>
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 content-center" style="text-align:center;">
                        <!-- Success message -->
                        @if(Session::has('success'))
                            <div class="alert alert-success">
                                {{Session::get('success')}}
                            </div>
                        @endif
                        <form method="post" action="{{ route('client.update', $client->id ) }}">
                            @method('PUT')
                            @csrf
                            <table style="margin:0 auto;">
                                <tr>
                                    <td>
                                        <label>Nazwa klienta</label>
                                    </td>
                                    <td>
                                        <input type="text" name="description" id="description" value="{{ $client->description }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Miasto</label>
                                    </td>
                                    <td>
                                        <input type="text" name="city" id="city" value="{{ $client->city }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Ulica nr. budynku / mieszkania</label>
                                    </td>
                                    <td>
                                        <input type="text" name="street" id="street" value="{{ $client->street }}"> <input type="text" name="parcel_number" id="parcel_number" value="{{ $client->parcel_number }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Kod pocztowy</label>
                                    </td>
                                    <td>
                                        <input type="text" name="post_code" id="post_code" value="{{ $client->post_code }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Kraj</label>
                                    </td>
                                    <td>
                                        <input type="text" name="country" id="country"  value="{{ $client->country }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Opcje</label>
                                    </td>
                                    <td>
                                        <input type="submit" name="send" value="Zapisz zmiany" class="btn btn-dark btn-block">
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>
