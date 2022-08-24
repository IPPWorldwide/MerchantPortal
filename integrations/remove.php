<?php
include("../base.php");

$ipp->RemovePlugin($company_data->content->id,$REQ["id"],$REQ["plugin"]);


$dir = "plugins/".$REQ["plugin"]."/".$company_data->content->id."_settings.php";
$utils->rrfile($dir);

