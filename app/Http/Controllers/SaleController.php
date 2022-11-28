<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sale;
use App\Models\UnitOfMeasurement;
use App\Http\Requests\SaveSaleRequest;
use App\Http\Requests\ChangeStatusSaleRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class SaleController extends Controller
{
  public function index()
  {
    $sales = Sale::with('customer','user','products','products.unitPresentationPrice','payments')->latest()->paginate(10);
    $unitOfMeasurement = UnitOfMeasurement::all();
    return Inertia::render('Sale/Index', ['sales' => $sales, 'unit_of_measurements' => $unitOfMeasurement]);
  }

  public function store(SaveSaleRequest $request){
    $prossecedRequest = $this->parseArraySale($request);
    $sale = Sale::create($prossecedRequest['sale']);
    foreach ($prossecedRequest['products'] as $product) {
      $sale->products()->attach($product);
    }
    if(count($prossecedRequest['payment']) > 0){
      $sale->payments()->create($prossecedRequest['payment']);
    }
    return Redirect::route('sales.index');
  }

  private function parseArraySale($request){
    $products = [];
    $total = 0;
    $payment = [];
    foreach($request->products as $product){
      $products[] = [$product['mango_presentation_id'] => ['price' => $product['current_price'], 'unit_of_measurement_id' => $product['unit_of_measurement_id'], 'quantity' => $product['quantity'], 'created_at' => now()]];
      $total += $product['quantity'] * $product['current_price'];
    }
    $sale = array_slice($request->all(),0,2);
    $sale['total'] = $total;
    $sale['user_id'] = Auth::user()->id;
    $sale['code'] = 'ORD'.now()->year.'-'.now()->timestamp;
    if($sale['payment_method'] == 2){ //Crédito
      if($request->payment['amount'] && $request['payment_type']){
        $payment = ['amount' => $request->payment['amount'], 'payment_type' => $request['payment_type'], 'user_id' => Auth::user()->id];
      }
    }else{
      $payment = ['amount' => $sale['total'], 'payment_type' => $request['payment_type'], 'user_id' => Auth::user()->id];
    }
    return ['sale' => $sale, 'products' => $products, 'payment' => $payment];
  }

  public function show($saleId)
  {
    return Sale::where('id', $saleId)->with('customer','user','products','products.unitPresentationPrice','payments')->first();
  }

  public function shipping(ChangeStatusSaleRequest $request, Sale $sale){
    $sale->update($request->validated());
    return Redirect::route('sales.index');
  }
}
