<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'client_id' => 'required',
            'client_order_number' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'client_id.required' => 'Podaj ID klienta',
            'client_order_number.required' => 'Podaj numer zamówienia Klienta',
        ];
    }
}
