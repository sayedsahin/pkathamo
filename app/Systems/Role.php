<?php declare(strict_types=1);

namespace App\Systems;

class Role{

	public static function guest()
	{
		Session::init();
		if (Session::get('login') === true) {
			header('Location:'.BASE_URL);
			exit;
		}
	}
	public static function auth()
	{
		Session::init();
		if (Session::get('login') === false) {
			if (isset($_COOKIE['token'])) {
				$session = Cookie::reSetSession();
				if ($session) {
					return;
				}
			}
			Session::destroy();
			if (!isAjax()) {
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