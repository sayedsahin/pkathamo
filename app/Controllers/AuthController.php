<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Middlewares\Authenticated;
use App\Middlewares\Guest;
use App\Supports\Auth;
use App\Supports\Role;
use App\Systems\Session\Cookie;
use App\Systems\Session\RememberToken;
use App\Validation\Validator;

class AuthController extends Controller
{

	public function __construct()
	{
		// $this->middleware(RateLimit::class);
		// $this->middleware(Csrf::class);
	}

	public function login()
	{
		return view('auth.login2', ['title' => 'Login']);
	}

	public function loginProcess()
	{

		/*
		|-----------------------------------------------------------
		| Validation way 1: Using Try-Catch
		|-----------------------------------------------------------
		*/

		$request = request();
		try {
			$data = Validator::make($request->all())
				->bail()
				->required(['email', 'password'])
				->nullable(['remember'])
				->email('email')
				// ->min('password', 8)
				->validated();

			// ✅ VALID DATA
			// $data['email']
			// $data['password']

			// dd($data);
		} catch (\App\Validation\ValidationException $e) {
			$errors = $e->errors();
			return redirect()->with(['errors' => $errors])->back();
		}

		$email = $data['email'];
		$password = $data['password'];

		$user = db()->table('users')
			->where('email', $email)
			->first();

		if (!$user || !password_verify($password, $user->password)) {
			return redirect()->with(['error' => 'Incorrect User or Password'])->back();
		}

		// Session::regenerate();
		// Session::set('auth_user_id', (int) $user->id);
		Auth::login((int) $user->id);
		Role::userRoles($user->id);
		if ($data['remember'] ?? false) {

			$token = RememberToken::generate();

			db()->table('remember_tokens')->insert([
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
		
		return redirect()->with(['success' => 'Login Successful'])->back();
		// return json(['ok' => true], 200);
	}

	public function registration()
	{
		$this->middleware(Guest::class);
		return view('auth.register', ['title' => 'Register']);
	}



	public function registrationProcess()
	{
		$this->middleware(Guest::class);

		/*
		|-----------------------------------------------------------
		| Validation way 2: Without Try-Catch, using fails() method
		|-----------------------------------------------------------
		*/
		$request = request();
		// dd($request->all());
		$validator = Validator::make($request->all())
			->required(['name', 'username', 'email', 'password', 'password_confirmation'])
			->string(['name', 'username', 'email', 'password', 'password_confirmation'])
			->email('email')
			// ->min(['password', 'password_confirmation'], 8)
			->confirmed('password');
		if ($validator->fails()) {
			$errors = $validator->errors();
			return redirect()->with(['errors' => $errors])->back();
		}
		$input = $validator->validated();

		if ($input['password'] !== $input['password_confirmation']) {
			return redirect()->with(['error' => 'Password confirmation does not match !'])->back();
		}

		$check_user = db()->table('users')
			->where('username', $input['username'])
			->count();

		if ($check_user) {
			return redirect()->with(['error' => 'username already exist'])->back();
		}

		$check_email = db()->table('users')
			->where('email', $input['email'])
			->count();

		if ($check_email) {
			return redirect()->with(['error' => 'email already exist'])->back();
		}

		unset($input['_csrf']);
		unset($input['agreed']);
		unset($input['password_confirmation']);

		$input['password'] = $password = password_hash($input['password'], PASSWORD_DEFAULT);

		$user_id = db()->table('users')->insert([
			'name' => $input['name'],
			'username' => $input['username'],
			'email' => $input['email'],
			'password' => $input['password'],
			'verification_token' => bin2hex(random_bytes(32)),
            'email_verified' => 0,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		], true);

		if (!$user_id) {
			return redirect()->with(['error' => 'Registration failed'])->back();
		}

		// assign default role
		Role::assign($user_id, 'user');


		return redirect()->with(['success' => 'Registration Successful'])->to('/login');
	}

	public function logout()
	{
		$this->middleware(Authenticated::class);
		$userId = Auth::id();

		if ($userId) {
			db()->table('remember_tokens')->where('user_id', $userId)->delete();
		}

		Auth::logout();
		return redirect('/login');
	}

	public function forgot()
	{
		$this->middleware(Guest::class);
		return view('forgot_password');
	}
}
