<?php 

use App\Libraries\Redirect;

function view(string $file, array $data = [])
{
	if ($data == true) {
		extract($data);
	}
	include 'app/Views/'.$file.'.php';
}

function helper(array $helpers = [])
{
	foreach ($helpers as $helper) {
		include_once 'app/Helpers/'.$helper.'.php';
	}
}
function redirect(string $link='')
{
	return new Redirect($link);
}
function isAjax() : bool
{
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
		return true;
	}else{
		return false;
	}
}
function session()
{
	return new \Systems\Session;
}
//For Development only
function pr($array)
{
	echo "<pre>";
	print_r($array);
	echo "</pre>";
	// exit();
}
function dd($array)
{
	echo "<pre>";
	var_dump($array);
	echo "</pre>";
	exit();
}
?>