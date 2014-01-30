<?php

	setSession();

	function setSession() {
		global $sessionType;

		if (session_status() !== PHP_SESSION_ACTIVE) {
			session_set_cookie_params(3600 * 4, '/', '.kuhlonline.com', true, true);
			session_start();
		}

		if (isset($_SESSION['type'])) {
		    $type                   = $_SESSION['type'];
		    $sessionType            = ($type == 'member') ? $type : 'guest';
		} else {
		    $_SESSION['created']    = time();
		    $sessionType            = 'guest';
		}
		
		$_SESSION['type'] 	        = $sessionType;		
	}


?>