<?php

    global $version;
    global $release;
    global $mode;
	$request 	= array();
	$mode		= initRequest($request);


	/** 
	 * Start Shell Session
	 */
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_set_cookie_params(900);
		session_start();
	}


	/**
	 * Shell headers
	 *
	$release	= 0;
	$version    = getVersion($release);
	 */


	/**
	 * Boot
	 */
	$ready 		= boot($mode, $request);


	function initRequest(array &$request = array()) {
		
		$requestType 	= "shell";
		$requestArgs	= $_SERVER['argv'];
		$requestScript	= $requestArgs[0];
		$requestExecuter= $requestArgs[1];
		$requestAPI		= true;
		$requestData	= array();

		unset($requestArgs[0]);
		unset($requestArgs[1]);
		$buffer	= array_values($requestArgs);

		if ((count($requestArgs) == 1) and (json_decode($buffer[0]))) {
			$requestData	= json_decode($buffer[0], true);
		} elseif (count($requestArgs) >= 1) {
			foreach ($requestArgs as $arg) {
				$parts	= explode('=', $arg, 2);
				$key	= $parts[0];
				$value 	= (isset($parts[1])) ? $parts[1] : null;

				if (!$key) continue;
				$requestData[$key]	= $value;
			}
		} else {
			$requestData	= array();
		}

		$request = array(
			'script'	=> $requestScript,
			'data'		=> $requestData,
			'type'		=> $requestType,
			'executer'	=> $requestExecuter,
			'api'		=> $requestAPI,
			'urp'		=> $requestData
		);

		return $requestType;
	}
	


	function getVersion($major = 0) {
	    $value  = null;
	    $lines  = explode("\n", `svn info`);
	    
	    foreach ($lines as $line) {
	        $parts  = explode(":", $line, 2);
	        $key    = trim($parts[0]);
	        if ($key != "Revision") continue;
	        
	        $value  = trim($parts[1]);
	        if ($value) break;
	    }
	    
	    return "$major.$value";
	}



	function boot($mode, $stdInput = array()) {
		global $workingFolder;
		global $request;
		global $printMsgs;

		$printMsgs		= ($mode == 'shell');
		$request 		= $stdInput;
		$workingFolder 	= getcwd();
		$bootstrap 		= include('init.php');

		return $bootstrap;
	}



?>