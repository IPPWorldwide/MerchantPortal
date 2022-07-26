<?php
include("../b.php");
$theme = $partner->purchaseTheme($REQ["themes"]);
$src = BASEDIR . "theme/" . $REQ["themes"] . "/";
$filename = $src . basename($theme->{$REQ["themes"]}->file);
$dirMode = 0755;
if (!file_exists($src))
    if (!mkdir($src, $dirMode, true) && !is_dir($src)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $src));
    }
sleep(1);
file_put_contents($filename, fopen($theme->{$REQ["themes"]}->file, 'r'));
$zip = new ZipArchive();
$res = $zip->open($filename);
if ($res === TRUE) {
    $zip->extractTo($src);
    $zip->close();

} else {
    throw new \RuntimeException(sprintf('Could not Unzip file at "%s"', $src));
}
unlink($filename);
