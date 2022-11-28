<?php

namespace App\Http\Controllers;

use App\Models\MangoPresentation;
use App\Models\UnitOfMeasurement;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\SaveMangoPresentationRequest;
use App\Http\Requests\UpdateMangoPresentationRequest;

class MangoPresentationController extends Controller
{
  public function index()
  {
    $products = MangoPresentation::with('unitPresentationPrice')->latest()->paginate(10);
    $unitOfMeasurement = UnitOfMeasurement::all();
    return Inertia::render('MangoPresentation/Index', ['products' => $products, 'unit_of_measurements' => $unitOfMeasurement]);
  }

  public function store(SaveMangoPresentationRequest $request)
  {
    $presentationPriceByUnit = $this->processUnits($request->validated()['unit']);
    $mangoPresentation = MangoPresentation::create($request->validated());
    $mangoPresentation->unitPresentationPrice()->attach($presentationPriceByUnit);
    if($mangoPresentation){
			return response()->json(['code' => 200, 'message' => 'Presentación agregada correctamente', 'success' => true]);
		}else{
			return response()->json(['code' => 500, 'message' => 'Algo salió mal, intente más tarde', 'success' => false]);
		}
  }

  private function processUnits($priceUnitsArray) 
  {
    $data = [];
    foreach($priceUnitsArray as $priceUnit){
      if($priceUnit['current_price']){
        $data[$priceUnit['unit_of_measurement_id']] = [
          'current_price' =>  $priceUnit['current_price'],
          'created_at' => now()
        ];
      }
    }
    return $data;
  }

  public function update(UpdateMangoPresentationRequest $request, MangoPresentation $mangoPresentation)
  {
    $mangoPresentation->update($request->validated());
    $data = [];
    foreach ($request->validated()['unit'] as $key => $unit) {
      if($unit['current_price']){
        $data[$unit['unit_of_measurement_id']] =  ['current_price' => $unit['current_price']];
      }
    }
    $mangoPresentation->unitPresentationPrice()->sync($data);
    return response()->json(['code' => 200, 'message' => 'Presentación actualizada correctamente', 'success' => true]);
  }

  public function destroy(MangoPresentation $mangoPresentation)
  {
      //
  }

  public function getAllRecords(Request $request){
    $mangoPresentations = MangoPresentation::when($request->search, function($query) use($request){
      $query->where('name', 'LIKE', '%'.$request->search.'%');
    })->with('unitPresentationPrice')->get();
		return response()->json(['code' => 200, 'message' => 'OK', 'presentations' => $mangoPresentations]);
	}
}
