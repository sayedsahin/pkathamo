<?php 

namespace App\Libraries;

use Systems\Session;
class Helper
{
	public function back()
	{
		if (isset($_SERVER['HTTP_REFERER'])) {
			$url = $_SERVER['HTTP_REFERER'];
			$host = parse_url($url, PHP_URL_HOST);
			$base = parse_url(BASE_URL, PHP_URL_HOST);
			if ($host == $base) {
				Session::set("link", $url);
			}else{
				Session::set("link", BASE_URL);
			}
	         
	    }else{
	    	Session::set("link", BASE_URL); 
	    }
	    header("Location:".Session::get("link"));
	}
}
?>