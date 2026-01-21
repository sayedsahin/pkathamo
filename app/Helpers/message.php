<?php 
	use Systems\Session;
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
 ?>