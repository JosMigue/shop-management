<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\SaveCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Inertia\Inertia;

class CustomerController extends Controller
{
	public function index()
	{
		$customers = Customer::withTrashed()->when(request()->has('search'), function($q) {
			return $q->where('name','like','%'.request()->search.'%');
		})->latest()->paginate(10)->withQueryString();
		return Inertia::render('Customer/Index', ['customers' => $customers, 'filters' => request()->all('search')]);
	}

	public function store(SaveCustomerRequest $request)
	{
		$customer = Customer::create($request->validated());
		if($customer){
			return response()->json(['code' => 200, 'message' => 'Cliente agregado correctamente', 'success' => true]);
		}else{
			return response()->json(['code' => 500, 'message' => 'Algo salió mal, intente más tarde', 'success' => false]);
		}
	}

	public function update(UpdateCustomerRequest $request, Customer $customer)
	{
		if($customer->update($request->validated())){
			return response()->json(['code' => 200, 'message' => 'Cliente actualizado correctamente', 'success' => true]);
		}else{
			return response()->json(['code' => 500, 'message' => 'Algo salió mal, intente más tarde', 'success' => false]);
		}
	}

	public function restore($customerId)
	{
		$customer = Customer::withTrashed()->find($customerId);
		$customer->restore();
		return response()->json(['code' => 200, 'message' => 'Cliente se activó correctamente', 'success' => true]);
	}

	public function destroy(Customer $customer)
	{
		if($customer->delete()){
			return response()->json(['code' => 200, 'message' => 'Cliente se desactivó correctamente', 'success' => true]);
		}else{
			return response()->json(['code' => 500, 'message' => 'Algo salió mal, intente más tarde', 'success' => false]);
		}
	}

	public function getAllRecords(){
		return response()->json(['code' => 200, 'message' => 'OK', 'customers' => Customer::all()]);
	}
}
