<?php
include("../b.php");
$src = BASEDIR."plugins/upd_".$REQ["plugin"]."/";
$filename = $src . basename($REQ["file"]);
$dirMode = 0755;
if(!file_exists($src))
    if (!mkdir($src, $dirMode, true) && !is_dir($src)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $src));
    }
sleep(1);
file_put_contents($filename, fopen($REQ["file"], 'r'));
$zip = new ZipArchive();
$res = $zip->open($filename);
if ($res === TRUE) {
    $zip->extractTo($src);
    $zip->close();
} else {
    throw new \RuntimeException(sprintf('Could not Unzip file at "%s"', $src));
}
unlink($filename);
$utils->cpy($src,BASEDIR."plugins/".$REQ["plugin"]."/");
$utils->recurseRmdir($src);
unlink(BASEDIR."plugins/".$REQ["plugin"]."/version.php");