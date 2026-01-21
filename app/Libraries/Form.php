<?php 
namespace App\Libraries;

use Systems\Session;
// https://github.com/rakit/validation For future
class Form
{
	public $currentValue;
	public $values = [];
	public $errors = [];

	public function post($key)
	{
		$this->values[$key] = trim(stripcslashes(htmlspecialchars($_POST[$key])));
		$this->currentValue = $key;
		return $this;
	}
	public function get($key)
	{
		$this->values[$key] = trim(stripcslashes(htmlspecialchars($_GET[$key])));
		$this->currentValue = $key;
		return $this;
	}
	public function required()
	{
		if (empty($this->values[$this->currentValue])) {
			$this->errors[$this->currentValue] = "Filled must not be empty !";
		}
		return $this;
	}
	public function catRequired()
	{
		if ($this->values[$this->currentValue] == 0) {
			$this->errors[$this->currentValue] = "Filled must not be empty !";
		}
		return $this;
	}
	public function length($min = 0, $max = 255)
	{
		if (strlen($this->values[$this->currentValue]) < $min OR strlen($this->values[$this->currentValue]) > $max) {
			$this->errors[$this->currentValue] = "Min ".$min." and max ".$max." characters ! ";
		}
		return $this;
	}
	public function length_utf8($min = 0, $max = 255)
	{
		if (mb_strlen($this->values[$this->currentValue]) < $min OR mb_strlen($this->values[$this->currentValue]) > $max) {
			$this->errors[$this->currentValue] = "Min ".$min." and max ".$max." characters ! ";
		}
		return $this;
	}
	public function email()
	{
		if (!filter_var($this->values[$this->currentValue], FILTER_VALIDATE_EMAIL)) {
			$this->errors[$this->currentValue] = "This email is not valid !";
		}
		return $this;
	}
	public function pregMatch()
	{
		if (preg_match('/[^a-z0-9_-]+/i', $this->values[$this->currentValue])) {
			$this->errors[$this->currentValue] = "Only use A-Z a-z 0-9 _ - ";
		}
		return $this;
	}
	public function submit()
	{
		if (empty($this->errors)) {
			return true;
		}else{
			return false;
		}
	}
	//Extra Function
	public function check($db, $table, $key, $value)
	{
		$data = [
			'where' => [$key => $value],
			'type'  => 'count'
		];
		$count = $db->select($table, $data);
		if ($count > 0) {
			return true;
		}else{
			return false;
		}
	}
}

?>