<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Customer;

class ReportController extends Controller
{
    public function index()
    {
        $customers = Customer::withTrashed()->with('sales','sales.user')->get();
        return Inertia::render('Report/Index', ['customers' => $customers]);
    }
}
