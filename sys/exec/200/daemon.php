<?php

	global $mode;
	if ($mode == 'shell' and initDaemon()) forkDaemon();

	function initDaemon() {
		global $daemon;
		$daemon = (isset($_SERVER['argv'][2]) and ($_SERVER['argv'][2] == 'server'));
		return $daemon;
	}

	function forkDaemon() {
		global $daemon;

		$daemon = new daemon();
		$daemon->start();
	}

?>