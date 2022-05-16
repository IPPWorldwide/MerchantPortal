<?php
include("../b.php");

$src = BASEDIR."plugins/".$REQ["plugin"]."/";

array_map('unlink', glob("$src/*.*"));
rmdir($src);
$partner->RemovePlugin($REQ["id"],$REQ["plugin"]);
$remove_pugin = new $REQ["plugin"]();
if(method_exists($remove_pugin,"hookRemove"))
    $remove_pugin->hookRemove($remove_pugin->plugin_id,$id,$session_id);
