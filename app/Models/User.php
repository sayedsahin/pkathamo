<?php 
namespace App\Models;

use App\Systems\QueryBuilder;

class User extends QueryBuilder
{
	// protected ?string $defaultConnection = 'sqlite';
	protected string $defaultTable = 'users';
	protected array $defaultSelect = ['name', 'email'];

}