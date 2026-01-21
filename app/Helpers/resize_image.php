<?php 
	function resize_image($file, $w, $h, $crop=FALSE) {
		list($width, $height) = getimagesize($file);
		$r = $width / $height;
		if ($crop) {
			if ($width > $height) {
				$width = ceil($width-($width*abs($r-$w/$h)));
			} else {
				$height = ceil($height-($height*abs($r-$w/$h)));
			}
			$newwidth = $w;
			$newheight = $h;
		} else {
			if ($w/$h > $r) {
				$newwidth = $h*$r;
				$newheight = $h;
			} else {
				$newheight = $w/$r;
				$newwidth = $w;
			}
		}
		$ex = explode('.', $file);
		$ex = end($ex);
		if ($ex == 'jpg' || $ex == 'jpeg' || $ex == 'jfif') {
			$src = imagecreatefromjpeg($file);
			$dst = imagecreatetruecolor($newwidth, $newheight);
		}elseif ($ex == 'png') {
			$src = imagecreatefrompng($file);
			$dst = imagecreatetruecolor($newwidth,$newheight);
			imagealphablending($dst, false);
			imagesavealpha($dst, true);	
		}
		imagecopyresampled($dst, $src,0,0,0,0,$newwidth,$newheight,$width, $height);
		return $dst;
	}
?>