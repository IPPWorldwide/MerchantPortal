<?php
if(!defined('BASEDIR')){ die(); }

$url = "https://github.com/IPPWorldwide/Sample-Prestashop/archive/refs/heads/main.zip";


file_put_contents(BASEDIR . "tmp/prestashop.zip", fopen($url, 'r'));

$zip = new ZipArchive();
$res = $zip->open(BASEDIR . "tmp/prestashop.zip");
if ($res === TRUE) {
    $zip->extractTo(BASEDIR . "tmp/prestashop/");
    $zip->close();
} else {
    throw new \RuntimeException(sprintf('Could not Unzip file at "%s"', BASEDIR . "tmp/prestashop/"));
}
unlink(BASEDIR . "tmp/prestashop.zip");
rename(BASEDIR . "tmp/prestashop/Sample-Prestashop-main/ps_ippgateway",BASEDIR . "tmp/new_prestashop/");
rename(BASEDIR . "tmp/new_prestashop/ps_ippgateway.php",BASEDIR . "tmp/new_prestashop/ps_new_prestashop.php");
sleep(1);

$str = file_get_contents(BASEDIR . "tmp/new_prestashop/ps_new_prestashop.php");
$str = str_replace("ps_ippgateway","ps_new_gateway", $str);
file_put_contents(BASEDIR . "tmp/new_prestashop/ps_new_prestashop.php", $str);
Zip(BASEDIR . 'tmp/new_prestashop/', BASEDIR . 'tmp/new_prestashop.zip');

