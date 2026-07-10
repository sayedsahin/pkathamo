<?php

namespace App\Controllers;

use App\Middlewares\Authenticated;
use App\Supports\Auth;
use App\Supports\Role;

// Home Controller for testing purpose only
class HomeController extends Controller
{
	// For Testing purpose only, you can remove this method in production
	public function index()
	{

		// return redirect('/login');
		// echo session()->get('auth_user_id');
		// return redirect('/login')->with(['error' => 'Unauthorized']);
		// $this->middleware(Authenticated::class);

		// $user = Auth::user();
		$users = db()->select('id', 'name')->table('users')->get();
		// return response()->json($users);
		// return response($users, 200);
		return view('home', [
			'title' => 'Home',
			'users' => $users
		]);
	}
}
