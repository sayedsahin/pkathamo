<?php
namespace App\Supports;
class Redirect
{
	protected string $link;
	protected array $message = [];

	public function __construct(string $link = '')
	{
		$this->to($link);
	}

	public function __destruct()
	{
		$this->redirect();
	}

	public function with(array $msg)
	{
		$this->message = $msg;
		return $this;
	}

	public function back()
	{
		if (isset($_SERVER['HTTP_REFERER'])) {
			$url = $_SERVER['HTTP_REFERER'];
			$host = parse_url($url, PHP_URL_HOST);
			$base = parse_url(BASE_URL, PHP_URL_HOST);
			if ($host == $base) {
				$this->link = $url;
			}else{
				$this->link = BASE_URL;
			}

	    }else{
	    	$this->link = BASE_URL;
	    }
	    return $this;
	}

	public function to(string $link = '')
	{
		$this->link = BASE_URL.$link;
		return $this;
	}

	public function redirect()
	{
		if (!empty($this->link)) {
			if (is_ajax()) {
				foreach ($this->message as $key => $value) {
					echo $key.' = '.$value.'<br>';
				}
			}else {
				if (!empty($this->message)) {
					$_SESSION['message'] = $this->message;
				}
				header("Location: " . $this->link);
				exit;
			}

		}else{
			foreach ($this->message as $key => $value) {
				echo $key.' = '.$value.'<br>';
			}
		}
	}

}
 ?>