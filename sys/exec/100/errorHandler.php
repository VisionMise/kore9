<?php

	/** Initialize Error Handler and Logging **/
	iniPHP_reporting();


	function iniPHP_reporting() {
		$settings		= new settings('settings');
    	$options 		= $settings->logger;

    	if ($options['phpReporting'] == false) error_reporting(0);
    	set_error_handler("errorHandler");
	}

	function errorHandler($errno, $errmsg, $filename, $linenum, $vars) {
		global $printMsgs;

	    $dt  			= date("Y-m-d H:i:s (T)");
	    $errortype 		= array (
	        E_ERROR              => 'Error',					// 1
	        E_WARNING            => 'Warning',					// 2
	        E_PARSE              => 'Parsing Error',			// 4
	        E_NOTICE             => 'Notice',					// 8
	        E_CORE_ERROR         => 'Core Error',				// 16
	        E_CORE_WARNING       => 'Core Warning',				// 32
	        E_COMPILE_ERROR      => 'Compile Error',			// 64
	        E_COMPILE_WARNING    => 'Compile Warning',			// 128
	        E_USER_ERROR         => 'User Error',				// 256
	        E_USER_WARNING       => 'User Warning',				// 512
	        E_USER_NOTICE        => 'User Notice',				// 1024
	        E_STRICT             => 'Runtime Notice',			// 2048
	        E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'		// 4096
	    );

	    $user_errors 	= array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);
	    $type 			= (in_array($errno, $user_errors)) ? 'user' : 'system';

	    $parts 			= array(
	    	'Date/Time'			=> $dt,
	    	'Error Number'		=> $errno,
	    	'Error Type'		=> $errortype[$errno],
	    	'Message'			=> $errmsg,
	    	'File'				=> $filename,
	    	'Line'				=> $linenum,
	    	'Variables'			=> $vars,
	    	'Remote Address'    => (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : 'Hosted'
    	);

    	/** Print Msgs **/
    	$strMsg			= "-- {$errortype[$errno]} -- [$dt #$errno]\nLINE# $linenum\n$errmsg\n$filename\n";
    	if ($printMsgs) printMsg($strMsg);


	    /** Output **/
    	//$strBuffer		= "<dl class=\"alert alert-box alert-danger err err-$errno\">\n";
    	$strBuffer 			= null;
    	foreach ($parts as $label => $value) {
    		if (is_array($value) or is_object($value)) {
    			$value 	= print_r($value, true);
    			//$class  = "err-value err-array";
    		} else {
    			//$class  = "err-value err-string";
    		}

    		//$strBuffer .= "\t<dt class=\"err-label\">$label</dt>\n";
    		//$strBuffer .= "\t\t<dd class=\"$class\">$value</dd>\n";
    		$strBuffer .= "$label: $value\n";
    	}
    	//$strBuffer 	   .= "</dl>\n";
		$strBuffer		.= "\n";

    	/** Options **/
    	$settings		= new settings('settings');
    	$options 		= $settings->logger;

    	/** Logger **/
	    $logger 		= new log($options['print'], $options['email'], $options['to']);
	    $logger->$type 	= $strBuffer;
	}

	function throwError($errorMessage, $errorCode, array $param = array()) {
		errorHandler($errorCode, $errorMessage, __FILE__, __LINE__, $param);
	}

?>