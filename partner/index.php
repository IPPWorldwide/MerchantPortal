<?php
include("b.php");
if(isset($REQ["update"]) && $REQ["update"] == "true") {
    header( "Location: /update.php?version=".$ipp->version()->content->version);
    die();
}
echo head();

if($_ENV["VERSION"] < $ipp->version()->content->version) {
    echo "<div class=\"alert alert-warning\" role=\"alert\">".$lang["PARTNER"]["DASHBOARD"]["OUTDATED_VERSION"]."<a href='?update=true'>".$lang["PARTNER"]["DASHBOARD"]["UPDATE_HERE"]."</a></div>";
    foreach($ipp->ListVersions() as $key=>$value) {
        echo "<div class=\"alert alert-warning\" role=\"alert\"><h3>$key - $value</h3><p>";
        $pageDocument = @file_get_contents("https://raw.githubusercontent.com/IPPWorldwide/MerchantPortal/".$key."/CHANGES.md");
        if ($pageDocument !== false) {
            echo nl2br($pageDocument);
        }
        echo "</p></div>";
    }
}

echo foot();

