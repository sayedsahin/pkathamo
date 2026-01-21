<?php 
	function flash($msg, $link='') {
		$fullmsg = "";
		if (is_array($msg)) { 
			$fullmsg .= '<p class="alert alert-danger d-inline-block">';
			foreach ($msg as $key => $value) {
                $fullmsg .= "* ".$value." (".$key.")<br>";
            }
            $fullmsg .= '</p>';
		}else{
			$color = (strpos($msg, '!') != false) ? 'danger' : 'success' ;
			$fullmsg .= '<p class="alert alert-'.$color.' d-inline-block">'.$msg.'</p>';
		}

		if (isset($_POST['ajax']) || isset($_GET['ajax'])) {
			echo $fullmsg;
		}else{
			Session::set("msg", $fullmsg);
			if (!empty($link)) {
				header("Location: ".BASE_URL.$link);
			}else{
				back();
			}
		}
	}
	function back() {
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

?>