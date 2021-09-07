<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;



class ClientRequest extends FormRequest

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
            'description' => 'required',
            'city' => 'required',
            'post_code' => 'required',
            'country' => 'required',
            'street' => 'required',
            'parcel_number' => 'required',
            'contact_number' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'description.reuired' => 'Podaj Nazwę firmy.',
            'city.required' => 'Podaj siedzibę firmy ( miasto ).',
            'post_code.required' => 'Podaj kod pocztowy.',
            'country.required' => 'Podaj kraj.',
            'street.required' => 'Podaj ulicę.',
            'parcel_number.required' => 'Podaj numer domu / mieszkania.',
            'contact_number.required' => 'Podaj telefoniczny numer kotaktowy.',
        ];
    }
}
