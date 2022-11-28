<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
  public function index()
  {
    $currentDaySales = Sale::whereDate('created_at', now())->sum('total');
    $yesterdaySales = Sale::whereDate('created_at', now()->subDay())->sum('total');
    $currentYearSales = Sale::whereYear('created_at', now()->year)->sum('total');
    $customersCount = Customer::get()->count();
    $currentTotalExpenses = Expense::where('user_id', Auth::user()->id)->sum('amount');
    $usersAreSubAdminId = User::where('role_id',Role::SUBADMIN)->get()->pluck('id');
    $totalExpenses = Auth::user()->isNotAdmin() ? Expense::whereIn('user_id', $usersAreSubAdminId)->sum('amount') :  Expense::sum('amount');
    return Inertia::render('Dashboard',['currentDaySales' => $currentDaySales, 'currentYearSales' => $currentYearSales, 'yesterdaySales' => $yesterdaySales, 'customersCount' => $customersCount, 'currentTotalExpenses' => $currentTotalExpenses, 'totalExpenses' => $totalExpenses]);
  }
}
