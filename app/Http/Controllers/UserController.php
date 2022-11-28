<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Inertia\Inertia;
use App\Http\Requests\SaveUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
  public function index()
  {
    $users = Auth::user()->isRoot() ? User::withTrashed()->where('role_id','!=',1)->when(request()->has('search'), function($q){
      return $q->where('name','like','%'.request()->search.'%');
    })->with('role')->latest()->paginate(5) : User::withTrashed()->whereNotIn('role_id',[Role::ROOT,Role::BOSS])->when(request()->has('search'), function($q){
      return $q->where('name','like','%'.request()->search.'%');
    })->with('role')->latest()->paginate(5);
    $roles = Auth::user()->isRoot() ? Role::where('id','!=',Role::ROOT)->get() : Role::whereNotIn('id',[Role::ROOT,Role::BOSS])->get();
    return Inertia::render('User/Index', ['users' => $users, 'roles' => $roles, 'filters' => request()->all('search')]);
  }

  public function store(SaveUserRequest $request)
  {
    $data = $request->validated();
    $data['password'] = Hash::make($data['password']);
    User::create($data);
    return Redirect::route('users.index');
  }

	public function restore($userId)
	{
		$user = User::withTrashed()->find($userId);
		$user->restore();
		return response()->json(['code' => 200, 'message' => 'Usuario se habilitó correctamente', 'success' => true]);
	}

  public function destroy(User $user){
		if($user->delete()){
			return response()->json(['code' => 200, 'message' => 'Usuario desactivado correctamente', 'success' => true]);
		}else{
			return response()->json(['code' => 500, 'message' => 'Algo salió mal, intente más tarde', 'success' => false]);
		}
  }
}
