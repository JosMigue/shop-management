<?php

namespace App\Http\Controllers;

use App\Models\UnitOfMeasurement;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\SaveUnitMeasurement;
use App\Http\Requests\UpdateUnitMeasurement;

class UnitOfMeasurementController extends Controller
{

    public function index()
    {
      $unitOfMeasurement = UnitOfMeasurement::paginate(10);
      return Inertia::render('UnitOfMeasurement/Index', ['unit_of_measurements' => $unitOfMeasurement]);
    }

    public function store(SaveUnitMeasurement $request)
    {
      UnitOfMeasurement::create($request->validated());
      return response()->json(['code' => 200, 'message' => 'Unidad de medida agregada correctamente', 'success' => true]);
    }
    
    public function update(UpdateUnitMeasurement $request, UnitOfMeasurement $unitOfMeasurement)
    {
      $unitOfMeasurement->update($request->validated());
      return response()->json(['code' => 200, 'message' => 'Unidad de medida actualizada correctamente', 'success' => true]);
    }
}
