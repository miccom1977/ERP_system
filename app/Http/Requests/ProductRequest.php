<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;



class ProductRequest extends FormRequest

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
            'roll_width' => 'required',
            'grammage' => 'required',
            'designation' => 'required',
            'count' => 'required',
            'cardboard_producer' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'description.required' => 'Dodaj opis papieru',
            'roll_width.required' => 'Dodaj szerokość rolki',
            'grammage.required' => 'Dodaj gramaturę papieru',
            'designation.required' => 'Dodaj literowe oznaczenie papieru',
            'count.required' => 'Dodaj ilość sztuk',
            'cardboard_producer.required' => 'Dodaj prducenta papieru',
        ];
    }
}
