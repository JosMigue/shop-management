<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Sale;
use Illuminate\Http\Request;
use App\Http\Requests\SavePaymentRequest;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function store(Sale $sale,SavePaymentRequest $request)
    {
        if(($sale->payments_sum + $request->validated()['amount']) <= $sale->total){
            $data = $request->validated();
            $data['user_id'] = Auth::user()->id;
            $sale->payments()->create($data);
            return response()->json(['code' => 200, 'message' => 'Pago agregado correctamente', 'success' => true]);
        }else{
            return response()->json(['code' => 500, 'message' => 'No se pudo agregar el pago, ha superado la cantidad calculada a la de la venta.', 'success' => false]);
        }
    }
}
