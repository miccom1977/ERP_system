<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderPositionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'quantity' => 'required',
            'l_elem' => 'required',
            'q_elem' => 'required',
            'h_elem' => 'required',
            'article_number' => 'required',
            'flaps_a' => 'required',
            'flaps_b' => 'required',
            'division_flapsL' => 'required',
            'division_flapsQ' => 'required',
            'l_elem_pieces' => 'required',
            'q_elem_pieces' => 'required',
            'packaging' => 'required',
            'product_id' => 'required',
            'pallets' => 'required',
            'date_shipment' => 'required',
            'date_production' => 'required',
            'date_delivery' => 'required',
            'order_id' => 'required',
            'custom_order_id' => 'required'
        ];
    }


    public function messages()
    {
        return [
            'quantity.required' => 'Podaj ilość sztuk',
            'l_elem.required' => 'Podaj szerokość elementu długiego L',
            'q_elem.required' => 'Podaj szerokość elementu krótkiego Q',
            'h_elem.required' => 'Podaj wysokość kratownicy',
            'article_number.required' => 'Podaj numer artykułu',
            'flaps_a.required' => 'Podaj ilość elementów długich L',
            'flaps_b.required' => 'Podaj ilość elementów krótkich Q',
            'division_flapsL.required' => 'Podaj szerokość celi',
            'division_flapsQ.required' => 'Podaj długość celi',
            'l_elem_pieces.required' => 'Podaj ilość elementów długich L',
            'q_elem_pieces.required' => 'Podaj ilość elementów krótkich Q',
            'packaging.required' => 'Podaj po ile kratownic pakować w paczkę',
            'product_id.required' => 'Podaj ID produktu',
            'pallets.required' => 'Podaj na jakie palety pakować',
            'date_shipment.required' => 'Podaj datę wysyłki do klienta',
            'date_production.required' => 'Podaj datę rozpoczęcia produkcji',
            'date_delivery.required' => 'Podaj datę dostawy do klienta',
            'order_id.required' => 'Podaj numer ID zlecenia',
            'custom_order_id.required' => 'Podaj numer zamówienia klienta',
        ];
    }
}
