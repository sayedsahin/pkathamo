<?php declare(strict_types=1);

namespace Systems;

use Systems\QueryBuilder;
use Systems\Session;

class Cookie{

	public static function reSetSession()
	{
		$model = new QueryBuilder;
		$token = $model->table('user_cookies')
			->where('token', $_COOKIE['token'])
			->where('expire_at', '>', 'now()')
			->where('user_agent', $_SERVER['HTTP_USER_AGENT'])
		->first();
		if ($token) {
			$user = $model->table('users')
				->where('id', $token->user_id)
				->first();

			Session::set("login", true);
			Session::set("id", (int) $user->id);
			Session::set("name", $user->name);
			Session::set("username", $user->username);
			Session::set("email", $user->email);
			Session::set("role", (int) $user->role_id);
			return true;
		}else{
			self::destroy();
		}
		return false;
	}


	public static function destroy()
	{
		$model = new QueryBuilder;
		$model->table('user_cookies')
			->where('token', $_COOKIE['token'])
			->delete();
		setcookie('token', '', time()-3600);
	}

}
?>