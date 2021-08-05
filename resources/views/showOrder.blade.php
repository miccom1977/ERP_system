<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Zamówienie nr. : ') }} {{ $order->custom_order_id }}/{{ date_format($order->created_at, 'Y') }} <a href="/print/{{$order->id}}"><button>Drukuj dokumenty</button></a>
            @if ( isset($order->file->path) )
                <a href="c://xampp/htdocs/public/{{$order->file->path}}" downolad ><button>pobierz rysunek</button></a>
                @endif
                <a href="/printCMR/{{$order->id}}"><button>Drukuj CMR</button></a>
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
                                    <label>Numer zamówienia</label>
                                </td>
                                <td>
                                    <label>Akcja</label>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    {{  $order->client->description }}
                                </td>
                                <td>
                                    {{ $order->custom_order_id }}
                                </td>
                                <td>
                                    <a href="/orderPosition/create"><button>Dodaj pozycję</button></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>
