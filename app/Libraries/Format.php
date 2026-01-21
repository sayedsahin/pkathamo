<?php
namespace App\Libraries;
use DateTime;
class Format{
	public function formatDate($date){
		return date('F j, Y, g:i a', strtotime($date));
	}

	public function textShorten($text, $limit = 400){
		$text = $text. " ";
		$text = substr($text, 0, $limit);
		$text = substr($text, 0, strrpos($text, ' '));
		$text = $text.".....";
		return $text;
	}

	function time_ago($datetime, $full = false) {
	    $now = new DateTime;
	    $ago = new DateTime($datetime);
	    $diff = $now->diff($ago);

	    $diff->w = floor($diff->d / 7);
	    $diff->d -= $diff->w * 7;

	    $string = array(
	        'y' => 'year',
	        'm' => 'month',
	        'w' => 'week',
	        'd' => 'day',
	        'h' => 'hour',
	        'i' => 'minute',
	        's' => 'second',
	    );
	    foreach ($string as $k => &$v) {
	        if ($diff->$k) {
	            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
	        } else {
	            unset($string[$k]);
	        }
	    }

	    if (!$full) $string = array_slice($string, 0, 1);
	    //return $string ? implode(', ', $string) . ' ago' : 'just now';
	    return $string ? implode(', ', $string) . '' : 'just now';
	}
	public function duration($duration)
	{
		$gmdate = ($duration < 3600) ? "i:s" : "H:i:s";
		return gmdate($gmdate, $duration);
	}
	function number_formatting($n) {
        $n = (0+str_replace(",","",$n));
        if(!is_numeric($n)){
        	return false;
        }
        if($n>999999999999) {
        	return round(($n/1000000000000),1).'T';
        }
        elseif($n>999999999) {
        	return round(($n/1000000000),1).'B';
        }
        elseif($n>999999) {
        	return round(($n/1000000),1).'M';
        }
        elseif($n>999) {
        	return round(($n/1000),1).'K';
        }
        return number_format($n);
    }
}
?>