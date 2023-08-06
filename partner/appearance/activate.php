<?php
include("../b.php");
$config = new IPPConfig();
$new_config = $config->UpdateConfig("THEME",$REQ["themes"]);
$config = $config->WriteConfig();
