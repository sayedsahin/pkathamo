<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Libraries\Form;
use App\Models\DB;
use App\Systems\QueryBuilder;
use App\Systems\Session\Cookie;
use App\Systems\Role;
use App\Systems\Session\RememberToken;
use App\Systems\Session\Session;
use App\Validation\Validator;

class AuthController extends Controller
{

	protected object $model;
	public function __construct()
	{
		// $this->middleware(RateLimit::class);
		// $this->middleware(Csrf::class);
		$this->model = new QueryBuilder;
	}

	public function index()
	{
		echo 123;
		return;
		phpinfo();
		return;
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
		return view('auth.login', ['title' => 'Login']);
	}

	public function loginRequest()
	{
		// Role::guest();
		// $data = null;
		// $errors = [];
		verify_csrf();
		// Validation way 1: Using Try-Catch

		$request = request();
		try {
			$data = Validator::make($request->all())
				->bail()
				->required(['email', 'password'])
				->nullable(['first_name', 'last_name', 'remember'])
				->string(['first_name', 'last_name'])
				->email('email')
				->min('password', 8)
				->confirmed('password')
				->validated();
			// ✅ VALID DATA
			// $data['email']
			// $data['password']

			// dd($data);
		} catch (\App\Validation\ValidationException $e) {
			$errors = $e->errors();
			// dd($errors);
		}

		// Validation way 2: Using Input Class with Method Chaining
		// $validator = Validator::make($request->all())
		// 	->required(['email', 'password'])
		// 	->email('email')
		// 	->min('password', 8);
		// if ($validator->fails()) {
		// 	$errors = $validator->errors();
		// 	return;
		// }
		// $data = $validator->validated();


		$email = $data['email'];
		$password = $data['password'];

		$user = $this->model->table('users')
			->where('email', $email)
			->first();

		if (!$user || !password_verify($password, $user->password)) {
			return redirect()->back()->with(['error' => 'Incorrect User or Password']);
		}

		Session::regenerate();
		Session::set('user_id', (int) $user->id);
		Session::set('role', $user->role ?? 'user');
		$db = new DB;
		if ($data['remember'] ?? false) {

			$token = RememberToken::generate();

			$db->table('remember_tokens')->insert([
				'user_id'    => $user->id,
				'token_hash' => $token['hash'],
				'expires_at' => date('Y-m-d H:i:s', time() + 86400 * 30),
				'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
				'created_at' => date('Y-m-d H:i:s'),
			]);

			Cookie::set(
				'remember_token',
				$token['raw'],
				86400 * 30,
				'Lax'
			);
		}

		// if (isset($_POST['remember'])) {
		// 	$token = hash('sha256', uniqid());
		// 	$ip_address = $this->getUserIP();
		// 	$user_agent = $_SERVER['HTTP_USER_AGENT'];
		// 	$expire_at = time()+60*60*24*90;
		// 	$this->model->table('user_cookies')->insert([
		// 		'user_id' => $user->id,
		// 		'token' => $token,
		// 		'ip_address' => $ip_address,
		// 		'user_agent' => $user_agent,
		// 		'expire_at' => date('Y-m-d H:i:s', $expire_at),
		// 	]);
		// 	setcookie('token', $token, $expire_at);
		// }

		// Session::set("login", true);
		// Session::set("id", (int) $user->id);
		// Session::set("name", $user->name);
		// Session::set("username", $user->username);
		// Session::set("email", $user->email);
		// Session::set("role", (int) $user->role_id);
		return redirect();
		// return json(['ok' => true], 200);
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

		if (!$input->submit()) {
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

		$input->values['password'] = $password = password_hash($input->values['password'], PASSWORD_DEFAULT);

		$user_id = $this->model->table('users')->insert($input->values, true);

		if (!$user_id) {
			return redirect()->back()->with(['error' => 'Registration failed']);
		}


		return redirect('/login')->with(['success' => 'Registration successfull']);
	}

	public function logout()
	{
		// Role::auth();
		// Session::destroy();
		// if (isset($_COOKIE['token'])) {
		// 	Cookie::destroy();
		// }

		$userId = Session::get('user_id');

		if ($userId) {
			$db = new DB();
			$db->table('remember_tokens')->where('user_id', $userId)->delete();
		}

		Session::destroy();

		Cookie::forget('remember_token');
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
