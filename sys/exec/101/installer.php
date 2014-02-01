<?php

    $dataFolder     = realpath(getcwd() . "/data");
    $stage          = checkInstallation($dataFolder);
    $ready          = processStage($stage);

    if (!$ready) {
        throwError("Could not process installation or validate files", 2048, true);
        exit();
    }

    function checkInstallation($dataFolder) {
        $appInstall = new settings('install', "$dataFolder/base-install/installation.conf");
        $ready      = true;

        foreach ($appInstall->installed as $switch => $installed) {
            if (!$installed) {
                $ready  = $switch;
                break 1;
            } 
        }

        if ($ready !== true) {
            $nextStep   = $ready;
        } else {
            $nextStep   = "run";
        }

        return $nextStep;
    }

    function processStage($stage) {
        switch ($stage) {
            case 'all_tables':
            break;

            case 'admin_group':
            break;

            case 'admin_user':
            break;

            default:
            case 'run':
            break;
        }

        return true;
    }


?>