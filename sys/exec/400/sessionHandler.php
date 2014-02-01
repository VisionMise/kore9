<?php

	global $userAccount;
	global $sessionType;
	global $seesionId;

	setSession($userAccount, $sessionType, $sessionId);

	//print_r($_SESSION);


	function setSession(&$userAccount = array(), &$sessionType = null, &$sessionId = 0) {

		/** 4 Day Cookie **/
		$days 	= ((3600 * 24) * 4);
		session_set_cookie_params($days, '/', '.kuhlonline.com', false, true);
		session_start();

		$sessionType 		= (isset($_SESSION['type'])) ? $_SESSION['type'] : 'guest';
		if ($sessionType != 'member') $sessionType = 'guest';
		$_SESSION['type']	= $sessionType;

		$skipKeys 		= array('type', 'guest');
		$sessionKeys	= array_keys($_SESSION);
		$sessionId		= null;
		$signedIn		= ($sessionType == 'member');

		foreach ($sessionKeys as $sKey) {
			if (in_array($sKey, $skipKeys)) continue;
			$sessionId	= $sKey;
			break;
		}

		$userAccount 	= (isset($_SESSION[$sessionId])) ? $_SESSION[$sessionId] : array();
		return $signedIn;
	}

	function _dep_setSession(&$user = array()) {
		global $sessionType;

		$days 	= ((3600 * 24) * 4);
		session_set_cookie_params($days, '/', '.kuhlonline.com', true, true);
		session_start();

		if (isset($_SESSION['type'])) {
		    $type                   = $_SESSION['type'];
		    $sessionType            = ($type == 'member') ? $type : 'guest';
		} else {
		    $_SESSION['guest']['created']    = time();
		    $sessionType            = 'guest';
		}
		
		$_SESSION['type'] 	        = $sessionType;		
	}


?>