<?php
include("b.php");
if(isset($REQ["update"]) && $REQ["update"] == "true") {
    header( "refresh:90;url=/partner/" );

    $partner->UpgradePortal();
    echo "Updating. Please wait 90 seconds";
    die();
}
echo head();

if($ipp->version()->content->version != $_ENV["version"]) {
    echo "<div class=\"alert alert-warning\" role=\"alert\">Your version of MerchantAdmin is outdated!<a href='?update=true'>Click here to update</a></div>";
}

echo foot();

