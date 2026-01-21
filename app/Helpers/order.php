<?php 
function orderBy(string $sort, string $key)
{
		if ($sort == "recent") {
			return $order = "{$key}_id DESC";
		}elseif ($sort == "old") {
			return $order = "{$key}_id ASC";
		}elseif ($sort == "view") {
			return $order = "{$key}_view DESC";
		}elseif ($sort == "popular") {
			return $order = "{$key}_react DESC";
		}else{
			return $order = "{$key}_id DESC";
		}

}
 ?>