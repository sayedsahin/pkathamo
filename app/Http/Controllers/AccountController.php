<?php declare(strict_types=1);

namespace App\Controllers;

use App\Libraries\Form;
use Systems\Cookie;
use Systems\QueryBuilder;
use Systems\Role;
use Systems\Session;

class AccountController
{
	protected object $model;
	public function __construct()
	{
		$this->model = new QueryBuilder;
	}

	public function index()
	{
		// phpinfo();
		// return;
		$users = $this->model->table('users')->get();
		pr($users);
	}

	public function login()
	{
		Role::guest();
		if (isset($_COOKIE['token'])) {
			$session = Cookie::reSetSession();
			if ($session) {
				return redirect();
			}
		}
		return view('login');
	}

	public function loginRequest()
	{
		Role::guest();
		$input = new Form;
		$input->post('username')->required();
		$input->post('password')->required();

		 if (!$input->submit()) {
			return redirect()->back()->with(['errors' => $input->errors]);
		}
	
		$username = $input->values['username'];
		$password = $input->values['password'];

		$user = $this->model->table('users')
			->where('username', $username)
			->where('password', md5($password))
			->first();

		if (!$user) {
			return redirect()->back()->with(['error' => 'Incorrect User or Password']);
		}

		if (isset($_POST['remember'])) {
			$token = hash('sha256', uniqid());
			$ip_address = $this->getUserIP();
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			$expire_at = time()+60*60*24*90;
			$this->model->table('user_cookies')->insert([
				'user_id' => $user->id,
				'token' => $token,
				'ip_address' => $ip_address,
				'user_agent' => $user_agent,
				'expire_at' => date('Y-m-d H:i:s', $expire_at),
			]);
			setcookie('token', $token, $expire_at);
		}

		Session::set("login", true);
		Session::set("id", (int) $user->id);
		Session::set("name", $user->name);
		Session::set("username", $user->username);
		Session::set("email", $user->email);
		Session::set("role", (int) $user->role_id);
		return redirect();
	}

	public function registration()
	{
		Role::guest();
		if (isset($_COOKIE['token'])) {
			$session = Cookie::reSetSession();
			if ($session) {
				return redirect();
			}
		}
		return view('registration');
	}



	public function registrationRequest()
	{
		Role::guest();
		$input = new Form;
		$input->post('name')->required()->length(1, 100); //Method Chaining
		$input->post('username')->required()->length(3, 100)->pregMatch();
		$input->post('email')->required()->email();
		$input->post('password')->required()->length(4, 20);
		$input->post('confirm_password')->required();

		if (!$input->submit()){
			return redirect()->back()->with(['errors' => $input->errors]);
		}

		if ($input->values['password'] !== $input->values['confirm_password']) {
			return redirect()->back()->with(['error' => 'confirm-password does not match !']);
		}

		$check_user = $this->model->table('users')
			->where('username', $input->values['username'])
			->count();

		if ($check_user) {
			return redirect()->back()->with(['error' => 'username already exist']);
		}

		$chec_email = $this->model->table('users')
			->where('email', $input->values['email'])
			->count();

		if ($chec_email) {
			return redirect()->back()->with(['error' => 'email already exist']);
		}

		unset($input->values['confirm_password']);
		
		$input->values['password'] = md5($input->values['password']);

		$user_id = $this->model->table('users')->insert($input->values, true);

		if (!$user_id) {
			return redirect()->back()->with(['error' => 'Registration failed']);
		}


		return redirect('/login')->with(['success' => 'Registration successfull']);
	}

	public function logout()
	{
		Role::auth();
		Session::destroy();
		if (isset($_COOKIE['token'])) {
			Cookie::destroy();
		}
		return redirect('/login');
	}

	public function forgot()
	{
		Role::guest();
		if (isset($_COOKIE['token'])) {
			$session = Cookie::reSetSession();
			if ($session) {
				return redirect();
			}
		}
		return view('forgot_password');
	}

	public function getUserIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}