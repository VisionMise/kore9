<?php

	global $mode;
	global $workingFolder;
	global $installer;

	$dataFolder	= realpath("$workingFolder/data");
	$installer 	= new installer($dataFolder, ($mode == 'shell'));

	if (!$installer->ready) {
		//do nothing - installer not present
	} elseif (!$installer->installed) {
		if ($mode == 'shell') {
			common::printMsg("Kore9 is not Installed. Exiting.");
			exit();
		} else {
			//show GUI
			/**

			*/
		}
	}





	

	class installer {

		protected $shell;
		protected $folder;

		public $ready 		= false;
		public $installed 	= false;

		function __construct($installFolder, $shellMode = false) {
			$this->folder 	= $installFolder;
			$this->shell 	= $shellMode;
			$this->ready 	= file_exists("{$this->folder}/base-install/");

			if (!$this->ready) return false;

			$header 		= "\n\t - Kore9 v".getVersion()." Installer -\n";
			common::printMsg($header);

			$this->installed= $this->init();

			if ($this->installed) {
				common::printMsg("\n[Server]\nEverything has been installed. \nPlease remove the folder:\n".realpath("{$this->folder}/base-install/"));
			} else {
				common::printMsg("Some or all of the above items still need installed");
				common::printMsg("Please run $ './k9 server'");
				common::printMsg("Then kore9 # 'install' ");
			}
		}

		private function init(array &$preq = array()) {
			if (!$this->folder) return false;
			$okay 		= true;

			$preq 		= array(
				'hasConnStr'	=> "Database host information",
				'canConnect'	=> "A valid database connection",
				'hasTables'		=> "Each required database table",
				'hasGroup'		=> "An admin group",
				'hasUser'		=> "An administrator"
			);

			foreach ($preq as $flag => $txt) {
				$func 		= "preq_$flag";
				$ready 		= $this->$func($preq, $flag);

				if ($this->shell) {
					$msg 	= ($ready) 
						? " [+] $txt is configured"
						: " [-] $txt needs configured"
					;

					common::printMsg($msg);
				}

				if (!$ready) $okay = false;
				$preq[$flag]= $ready;
			}

			return $okay;
		}

		private function preq_hasConnStr(&$preq, $flag) {
			$settings	= new settings("settings");
			$dbConnStr	= $settings->database;
			$req 		= array('host', 'user', 'schema');

			foreach ($req as $key) {
				if (!array_key_exists($key, $dbConnStr)) {
					$preq[$flag]	= false;
					return false;
				}
			}
			
			$preq[$flag]= true;
			return $preq[$flag];
		}

		private function preq_canConnect(&$preq, $flag) {
			if (!$preq['hasConnStr']) return false;

			$settings		= new settings("settings");
			$dbConnStr		= $settings->database;

			$sqlConnection  = new mysqli(
	            $dbConnStr['host'],
	            $dbConnStr['user'],
	            $dbConnStr['password'],
	            $dbConnStr['schema'],
	            $dbConnStr['port']
	        );

	        if ($sqlConnection->connect_errno) $preq[$flag] = false;
	        return $preq[$flag];
		}

		private function preq_hasTables(&$preq, $flag) {
			if (!$preq['canConnect']) return false;

			$settings		= new settings("settings");
			$dbConnStr		= $settings->database;

			$sqlConnection  = new mysqli(
	            $dbConnStr['host'],
	            $dbConnStr['user'],
	            $dbConnStr['password'],
	            $dbConnStr['schema'],
	            $dbConnStr['port']
	        );

	        if ($sqlConnection->connect_errno) {
	        	$preq[$flag] = false;
	        	return $preq[$flag];
        	}

	        $results	= $sqlConnection->query("SHOW TABLES;");
	        $dbName 	= $dbConnStr['schema'];
	        $reqTables 	= $settings->requiredTables;
	        $hasTables 	= true;

	        while ($table = $results->fetch_assoc()) {
	        	$tables[]	= $table["Tables_in_$dbName"];
	        }

	        foreach ($reqTables as $script => $tableSet) {
	        	foreach ($tableSet as $table) {
		        	if (!in_array($table, $tables)) {
		        		if ($this->shell) common::printMsg("     - The table '$table' is missing (script: $script.sql)");
		        		$hasTables 	= false;
		        	}
	        	}
	        }

	        $preq[$flag]	= $hasTables;
	        return $preq[$flag];
		}

		private function preq_hasGroup(&$preq, $flag) {			
			if (!$preq['canConnect']) return false;

			$settings		= new settings("settings");
			$dbConnStr		= $settings->database;

			$sqlConnection  = new mysqli(
	            $dbConnStr['host'],
	            $dbConnStr['user'],
	            $dbConnStr['password'],
	            $dbConnStr['schema'],
	            $dbConnStr['port']
	        );

	        if ($sqlConnection->connect_errno) {
	        	$preq[$flag] = false;
	        	return $preq[$flag];
        	}

	        $results		= $sqlConnection->query("SELECT * FROM `groups` WHERE admin = 1;");
	        $preq[$flag] 	= ($results->num_rows >= 1);
	        return $preq[$flag];
		}

		private function preq_hasUser(&$preq, $flag) {
			if (!$preq['canConnect']) return false;

			$settings		= new settings("settings");
			$dbConnStr		= $settings->database;

			$sqlConnection  = new mysqli(
	            $dbConnStr['host'],
	            $dbConnStr['user'],
	            $dbConnStr['password'],
	            $dbConnStr['schema'],
	            $dbConnStr['port']
	        );

	        if ($sqlConnection->connect_errno) {
	        	$preq[$flag] = false;
	        	return $preq[$flag];
        	}

	        $results		= $sqlConnection->query(
	        	"SELECT a.id as 'uid', g.id as 'gid', g.admin FROM kore9.accounts a LEFT JOIN groups g ON (a.`group` = g.id) WHERE g.admin = 1;"
        	);
        	
	        $preq[$flag] 	= ($results->num_rows >= 1);
	        return $preq[$flag];
		}



	}

?>