<?php
	function dateFormat($date){
		return date('F j, Y, g:i a', strtotime($date));
	}
	function time_ago($datetime, $full = false) {
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);

		$weeks = floor($diff->d / 7);
		$days = $diff->d - ($weeks * 7);

		$string = array(
			'y' => $diff->y,
			'm' => $diff->m,
			'w' => $weeks,
			'd' => $days,
			'h' => $diff->h,
			'i' => $diff->i,
			's' => $diff->s,
		);
		$labels = array('y' => 'year', 'm' => 'month', 'w' => 'week', 'd' => 'day', 'h' => 'hour', 'i' => 'minute', 's' => 'second');
		foreach ($string as $k => $v) {
			if ($v) {
				$string[$k] = $v . ' ' . $labels[$k] . ($v > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}

	    if (!$full) $string = array_slice($string, 0, 1);
	    //return $string ? implode(', ', $string) . ' ago' : 'just now';
	    return $string ? implode(', ', $string) . '' : 'just now';
	}
	function duration($duration)
	{
		$gmdate = ($duration < 3600) ? "i:s" : "H:i:s";
		return gmdate($gmdate, $duration);
	}
 ?>