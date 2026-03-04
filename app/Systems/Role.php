<?php declare(strict_types=1);

namespace App\Systems;

use App\Systems\Session\Session;

class Role{

	public static function guest()
	{
		// Session::init();
		if (Session::get('user_id')) {
			header('Location:'.BASE_URL);
			exit;
		}
	}
	public static function auth()
	{
		// Session::init();
		if (!Session::get('user_id')) {
			if (isset($_COOKIE['token'])) {
				$session = Cookie::reSetSession();
				if ($session) {
					return;
				}
			}
			Session::destroy();
			if (!is_ajax()) {
				return redirect('/login')->with(['error' => 'you must be login']);
			}else{
				echo 'login';
			}
			exit;
		}
	}


	public static function user()
	{
		self::auth();
		if(auth()->status === '2'){
			Session::clear();
			if (isset($_COOKIE['token'])) {
				Cookie::destroy();
			}
			return redirect('/login')->with(['error' => 'your account is pendding. please wait for approve or contact with admin.']);
			exit;
		}elseif (auth()->status === '3') {
			Session::clear();
			if (isset($_COOKIE['token'])) {
				Cookie::destroy();
			}
			return redirect('/login')->with(['error' => 'sorry ! your account is blocked']);
			exit;
		}
	}

	public static function admin()
	{
		self::user();
		if(Session::get('role') !== 3){
			return redirect('/');
		}
	}

}
?>