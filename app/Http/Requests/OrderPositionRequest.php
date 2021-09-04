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
<<<<<<< HEAD
            'custom_order_id' => 'required'
=======
            'custom_order_id' => 'required',
>>>>>>> c6a660c93b0a837af0482aeccd2f807511b43258
        ];
    }
}
