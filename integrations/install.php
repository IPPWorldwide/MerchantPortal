<?php
include_once("../base.php");
$slug = $REQ["plugin"];
$ipp->InstallPlugin($company_data->content->id,$slug);
