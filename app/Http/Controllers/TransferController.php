<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\SaveTransferRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class TransferController extends Controller
{
    public function index()
    {
      $transfers = Transfer::with('receiver','sender')->paginate(10);
      $users = User::where('role_id',4)->get();
      return Inertia::render('Transfer/Index', ['transfers' => $transfers, 'users' => $users]);
    }

    public function store(SaveTransferRequest $request)
    {
      $data = $request->validated();
      $data['sender_id'] = Auth::user()->id;
      Transfer::create($data);
      return Redirect::route('transfers.index');
    }

    public function show(Transfer $transfer)
    {
        //
    }

    public function destroy(Transfer $transfer)
    {
      $transfer->delete();
      return response()->json(['code' => 200, 'message' => 'Transferencia eliminada correctamente']);
    }
}
