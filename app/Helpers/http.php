<?php

use App\Systems\Session;
use App\Http\Request;
use App\Http\Response;
use App\Http\JsonResponse;

if (!function_exists('request')) {
    function request(): Request
    {
        static $req;

        if (! $req) {
            $req = Request::capture();
        }

        return $req;
    }
}

if (!function_exists('response')) {
    function response(string $content = '', int $status = 200): Response
    {
        return new Response($content, $status);
    }
}

if (!function_exists('json')) {
    function json(array $data, int $status = 200): JsonResponse
    {
        return new JsonResponse($data, $status);
    }
}


if (!function_exists('flash')) {
	function flash($msg, $link='') {
		$fullMsg = "";
		if (is_array($msg)) {
			$fullMsg .= '<p class="alert alert-danger d-inline-block">';
			foreach ($msg as $key => $value) {
				$fullMsg .= "* ".$value." (".$key.")<br>";
			}
			$fullMsg .= '</p>';
		}else{
			$color = (strpos($msg, '!') != false) ? 'danger' : 'success' ;
			$fullMsg .= '<p class="alert alert-'.$color.' d-inline-block">'.$msg.'</p>';
		}

		if (isset($_POST['ajax']) || isset($_GET['ajax'])) {
			echo $fullMsg;
		}else{
			Session::set("msg", $fullMsg);
			if (!empty($link)) {
				header("Location: ".BASE_URL.$link);
			}else{
				back();
			}
		}
	}
}


if (!function_exists('back')) {
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
}

if (!function_exists('message')) {
	function message()
	{
		if (!empty($_SESSION['message'])) {
			$msg = Session::get("message");
			if (!empty($msg['errors'])) {
				echo "<div class='alert alert-danger'>";
				foreach ($msg as $key => $value) {
					foreach ($value as $k => $val) {
						echo "* ".$val." (".$k.")<br>";
					}
				}
				echo "</div>";
			}elseif(!empty($msg['error'])) {
				echo "<div class='alert alert-danger'>".$msg['error']."</div>";
			}else {
				echo "<div class='alert alert-success'>".$msg['success']."</div>";
			}
			unset($_SESSION['message']);
		}
	}
}

if (!function_exists('redirect')) {
	function redirect(string $link='') {
		return new \App\Libraries\Redirect($link);
	}
}

function is_ajax() : bool
{
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
		return true;
	}else{
		return false;
	}
}

// function send_api_headers(): void
// {
//     header('Content-Type: application/json; charset=utf-8');
//     header('Cache-Control: no-store');
//     header('X-Content-Type-Options: nosniff');
// }

// function send_web_headers(): void
// {
//     header('X-Frame-Options: SAMEORIGIN');
//     header('X-Content-Type-Options: nosniff');
//     header('Referrer-Policy: strict-origin-when-cross-origin');

//     header(
//         "Content-Security-Policy: " .
//         "default-src 'self'; " .
//         "script-src 'self'; " .
//         "style-src 'self' 'unsafe-inline'; " .
//         "img-src 'self' data:;"
//     );
// }

function is_api_request(): bool
{
    $uri = $_SERVER['REQUEST_URI'] ?? '';

    if (str_starts_with($uri, '/api')) {
        return true;
    }

    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';

    // API clients usually explicitly prefer JSON
    if ($accept === 'application/json' || str_starts_with($accept, 'application/json')) {
        return true;
    }

    return false;
}