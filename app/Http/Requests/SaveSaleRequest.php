<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveSaleRequest extends FormRequest
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
            'customer_id' => 'required|integer',
            'payment_method' => 'required|in:1,2',
            'payment_type' => 'required|in:1,2',
            'payment' => 'nullable|array',
            'payment.amount' => 'nullable|numeric|gt:0',
            'products' => 'required|array',
            'products.*.mango_presentation_id' => 'required|exists:mango_presentations,id',
            'products.*.quantity' => 'required|numeric|gt:0',
            'products.*.unit_of_measurement_id' => 'required|integer|exists:unit_of_measurements,id',
            'products.*.current_price' => 'required|numeric|gt:0'
        ];
    }

    public function messages(){
        return [
            'products.required' => 'Debe seleccionar productos para poder proceder con la venta',
            'products.*.quantity.required' => "El campo cantidad es obligatorio.",
            'products.*.quantity.gt' => "El campo cantidad debe ser mayor que 0.",
            'payment.amount.gt' => 'El monto debe ser un valor mayor a 0',
            'payment_method.required' => 'Debe seleccionar un método de pago.',
            'payment_type.required' => 'Debe seleccionar el tipo de pago.',
            'products.*.measurement.unit_of_measurement_id.required' => 'Debe seleccionar una unidad de medida',
            'products.*.measurement.unit_of_measurement_id.exists' => 'Debe seleccionar una unidad de medida válida',
            'products.measurement.required' => 'Debe seleccionar las unidades de medidas para cada producto'
        ];
    }
}
