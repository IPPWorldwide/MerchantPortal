<?php
include("../b.php");

if($IPP_CONFIG["THEME"] !== $REQ["plugin"]) {
    $dir = "theme/".$REQ["plugin"];
    $utils->rrmdir($dir);

    var_dump($dir);
}