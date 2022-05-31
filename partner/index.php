<?php
include("b.php");
if(isset($REQ["update"]) && $REQ["update"] == "true") {
    header( "Location: /update.php?version=".$ipp->version()->content->version);
    die();
}
echo head();

if($_ENV["VERSION"] < $ipp->version()->content->version) {
    echo "<div class=\"alert alert-warning\" role=\"alert\">".$lang["PARTNER"]["DASHBOARD"]["OUTDATED_VERSION"]."<a href='?update=true'>".$lang["PARTNER"]["DASHBOARD"]["UPDATE_HERE"]."</a></div>";
}

echo foot();

