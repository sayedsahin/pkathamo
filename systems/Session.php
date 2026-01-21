<?php declare(strict_types=1);

namespace Systems;

class Session{

	public static function init(){
		if (session_status() === PHP_SESSION_NONE) {
			// ini_set('session.gc_maxlifetime', '14400'); //4 hours
			session_start();
		}
	}

	public static function get($key){
		if (isset($_SESSION[$key])) {
			return $_SESSION[$key];
		} else {
			return false;
		}
	}
	 
	public static function set($key, $val)
	{
		$_SESSION[$key] = $val;
	}

	public static function remove(string $key)
	{
		if (isset($_SESSION[$key])) {
			unset($_SESSION[$key]);
		}
	}

	public static function clear()
	{
		session_unset();
	}

	public static function destroy()
	{
		session_destroy();
	}
}