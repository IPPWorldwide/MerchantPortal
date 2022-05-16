<?php
include("../b.php");

$src = BASEDIR."plugins/".$REQ["plugin"]."/";
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
    $install = $partner->InstallPlugin($REQ["plugin"]);
    $myfile = fopen(BASEDIR . "plugins/".$REQ["plugin"]."/settings.php", "w") or die("Unable to open file!");
    $txt = "<?php\n";
    $txt .= "\$settings[\"plugin_id\"] = '" . $install->plugin_id . "';\n";
    fwrite($myfile, $txt);
    fclose($myfile);
    require_once BASEDIR . "plugins/".$REQ["plugin"]."/init.php";
    $new_pugin = new $REQ["plugin"]();
    if(method_exists($new_pugin,"hookInstall"))
        $new_pugin->hookInstall($install->plugin_id,$id,$session_id);
} else {
    throw new \RuntimeException(sprintf('Could not Unzip file at "%s"', $src));
}
unlink($filename);
