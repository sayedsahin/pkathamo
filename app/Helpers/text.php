<?php
	function textShorten($text, $limit = 400){
		$text = $text. " ";
		$text = mb_substr($text, 0, $limit);
		$text = mb_substr($text, 0, mb_strrpos($text, ' '));
		$text = $text."...";
		return $text;
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
?>