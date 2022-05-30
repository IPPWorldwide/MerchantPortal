<?php
include("b.php");
if(isset($REQ["update"]) && $REQ["update"] == "true") {
    header( "url=/update/?version=".$ipp->version()->content->version);
    die();
}
echo head();

if($ipp->version()->content->version != $_ENV["VERSION"]) {
    echo "<div class=\"alert alert-warning\" role=\"alert\">Your version of MerchantAdmin is outdated!<a href='?update=true'>Click here to update</a></div>";
}

echo foot();

