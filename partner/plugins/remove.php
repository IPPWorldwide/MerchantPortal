<?php
include("../b.php");

$partner->RemovePlugin($REQ["id"],$REQ["plugin"]);
$remove_pugin = new $REQ["plugin"]();
if(method_exists($remove_pugin,"hookRemove"))
    $remove_pugin->hookRemove($remove_pugin->plugin_id,$id,$session_id);

$dir = "plugins/".$REQ["plugin"];
$utils->rrmdir($dir);

