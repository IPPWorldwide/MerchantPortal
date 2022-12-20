<?php
if(!defined('BASEDIR')){ die(); }

$url = "https://github.com/IPPWorldwide/Sample-WooCommerce/archive/refs/heads/main.zip";


file_put_contents(BASEDIR . "tmp/woocommerce.zip", fopen($url, 'r'));

$zip = new ZipArchive();
$res = $zip->open(BASEDIR . "tmp/woocommerce.zip");
if ($res === TRUE) {
    $zip->extractTo(BASEDIR . "tmp/woocommerce/");
    $zip->close();
} else {
    throw new \RuntimeException(sprintf('Could not Unzip file at "%s"', BASEDIR . "tmp/woocommerce/"));
}
unlink(BASEDIR . "tmp/woocommerce.zip");
rename(BASEDIR . "tmp/woocommerce/Sample-WooCommerce-main/ippgateway",BASEDIR . "tmp/new_woocommerce/");
sleep(1);
rename(BASEDIR . "tmp/new_woocommerce/ippgateway.php",BASEDIR . "tmp/new_woocommerce/new_woocommerce.php");

$str = file_get_contents(BASEDIR . "tmp/new_woocommerce/new_woocommerce.php");
$str = str_replace("WC_Gateway_IPPGateway","WC_Gateway_".str_replace([" ",".","_",","],"",strtolower($_POST["portal_title"])), $str);
$str = str_replace("IPPGATEWAY_DIR",str_replace([" ",".","_",","],"",strtolower($_POST["portal_title"]))."_DIR", $str);
$str = str_replace("IPPGateway Services",$_POST["portal_title"], $str);
$str = str_replace("ippgateway",str_replace([" ",".","_",","],"",strtolower($_POST["portal_title"]))."_gateway", $str);
$str = str_replace("ipp_hourly",str_replace([" ",".","_",","],"",strtolower($_POST["portal_title"]))."_hourly", $str);
$str = str_replace("IPPGateway Payment",$_POST["portal_title"], $str);
file_put_contents(BASEDIR . "tmp/new_woocommerce/new_woocommerce.php", $str);
Zip(BASEDIR . 'tmp/new_woocommerce/', BASEDIR . 'tmp/new_woocommerce.zip');
rename(BASEDIR . 'tmp/new_woocommerce.zip', BASEDIR . 'ecommerce/woocommerce.zip');
