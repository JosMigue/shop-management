<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\SaveExpenseRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
  public function index()
  {
    $expenses = Auth::user()->isRoot() ? Expense::with('user')->paginate('10') : Expense::where('user_id', Auth::user()->id)->paginate('10');
    $currentTotalExpenses = Auth::user()->isRoot() ? Expense::whereYear('created_at',now()->year)->sum('amount') : Expense::where('user_id', Auth::user()->id)->sum('amount');
    return Inertia::render('Expense/Index',['expenses' => $expenses, 'currentTotalExpenses' => $currentTotalExpenses]);
  }

  public function store(SaveExpenseRequest $request)
  {
    if(Auth::user()->isNotAdmin()){
      $currentTotalExpenses = Expense::where('user_id', Auth::user()->id)->sum('amount');
      if((Auth::user()->sum_transfers - $currentTotalExpenses) <= $request->validated()['amount']){
        return Redirect::route('expenses.index')->with('error', 'Parece que ha superado el saldo establecido.');
      }
    }
    $data = $request->validated();
    $data['ref'] = 'EXPEN'.now()->year.'-'.now()->timestamp;
    Auth::user()->expenses()->create($data);
    return Redirect::route('expenses.index');
  }

  public function destroy(Expense $expense)
  {
    $expense->delete();
    return response()->json(['code' => 200, 'message' => 'Gasto borrado correctamente']);
  }
}
