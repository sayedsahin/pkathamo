<?php 
namespace App\Models;

use App\Systems\QueryBuilder;

class User extends QueryBuilder
{
	// $table use one function one time. 2nd time not working
	protected string $table = 'users';


}
?>