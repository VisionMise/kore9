<?php

	if (!checkInstallation()) exit();

	function checkInstallation() {
		$dataFolder     = realpath(getcwd() . "/data");
		$appInstall 	= new settings('install', "$dataFolder/base-install/installation.conf");
		$ready      	= true;

		foreach ($appInstall->installed as $switch => $installed) {
            if (!$installed) {
                $ready  = $switch;
                break 1;
            } 
        }

        if ($ready === true) return true;

        $currentStage	= $ready;
        $uiPath			= "$dataFolder/base-install/setup/$currentStage.phtml";

        include($uiPath);
        return false;

	}

?>