<?php
include "lib/noid/Noid.php";
require_once "NoidUI.php";

if (isset($_GET['q'])) {
    $uid = str_replace("ark:/", "", $_GET['q']);
    $dirs = scandir(NoidUI::dbpath());
    if (is_array($dirs) && count($dirs) > 2) {
        $noidUI = new NoidUI();
        $url  = "";
        foreach ($dirs as $dir) {
            if (!in_array($dir, ['.', '..'])) {

                // execute the command with entered params
                $result = $noidUI->exec_command("get " . $uid . ' ' . "PID", $noidUI->path($dir));
                var_dump($result);
                if (strlen($result) > 4) {
                    $pid = trim(preg_replace('/\s\s+/', ' ', $result));

                    // get current databaase
                    $metadata = NoidUI::getDatabaseInfo(NoidUI::dbpath($dir), $dir);

                    // refresh the page to destroy post section
                    $url = "http://$metadata->enterRedirect/islandora/object/$pid";

                    break;
                }

            }
        }
        header("Location: $url");
    }
}
else {
    print "invalid argument";
}
