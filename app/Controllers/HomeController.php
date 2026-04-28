<?php

namespace App\Controllers;

use App\Middlewares\Authenticated;
use App\Supports\Auth;
use App\Supports\Role;

// Home Controller for testing purpose only
class HomeController extends Controller
{
    public function index()
    {
		$this->middleware(Authenticated::class);
		pr($user = Auth::user());
		pr(Role::userRoles($user->id));
        $result['users'] = db()->table('users')->get();
		$result['tokens'] = db()->table('remember_tokens')->get();
		pr($result);
		return;
		$auth = Auth::user();
		echo pr($auth);
		return;
		phpinfo();
		return;
		$users = $this->model->table('users')->get();
		pr($users);
    }
}
