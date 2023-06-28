<?php
include("../b.php");
include(BASEDIR . "controller/IPPConfig.php");
$config = new IPPConfig();
$new_config = $config->UpdateConfig("THEME",$REQ["themes"]);
$config = $config->WriteConfig();
