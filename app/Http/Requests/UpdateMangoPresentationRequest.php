<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMangoPresentationRequest extends FormRequest
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
            'name' => 'required|string',
            'unit' => 'required|array',
            'unit.*.unit_of_measurement_id' => 'required|integer|exists:unit_of_measurements,id',
            'unit.*.current_price' => 'nullable|numeric|gt:0'
        ];
    }

    public function messages(){
        return [
            'unit.*.current_price.numeric' => "El campo precio no es válido.",
            'unit.*.current_price.gt' => "El campo precio debe ser mayor que 0."
        ];
    }
}
