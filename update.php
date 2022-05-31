<?php
$public_page=1;
include "base.php";
function listFolderFiles($dir)
{
    $fileInfo     = scandir($dir);
    $allFileLists = [];
    foreach ($fileInfo as $folder) {
        if ($folder !== '.' && $folder !== '..') {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $folder) === true) {
                $allFileLists[$folder] = listFolderFiles($dir . DIRECTORY_SEPARATOR . $folder);
            } else {
                $allFileLists[$folder] = $folder;
            }
        }
    }
    return $allFileLists;
}
function flatten($array, $prefix = '') {
    $result = array();
    foreach($array as $key=>$value) {
        if(is_array($value)) {
            $result = $result + flatten($value, $prefix . $key . '/');
        }
        else {
            $result[$prefix . $key] = $value;
        }
    }
    return $result;
}
function folder_copy($src,$dst) {
    $array = [];
    $dir = opendir($src);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file)) {
                folder_copy($src . '/' . $file, $dst."/".$file);
            }
            if ( !is_dir($src . '/' . $file)) {
                if(file_exists($dst."/".$file) && !is_dir($dst . '/' . $file))
                    unlink($dst."/".$file);
                if(!is_dir(dirname($dst."/".$file))) {
                    if (!mkdir($concurrentDirectory = dirname($dst . "/" . $file)) && !is_dir($concurrentDirectory)) {
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
                    }
                }
                if(!is_dir($dst . '/' . $file))
                    rename( $src . '/' . $file,  $dst."/".$file);
            }
        }
    }
    closedir($dir);
    rmdir($src);
}
$current_files = listFolderFiles('.');

file_put_contents("update/".$REQ["version"].".zip", fopen("https://github.com/IPPWorldwide/MerchantPortal/archive/refs/tags/".$REQ["version"].".zip", 'r'));

$zip = new ZipArchive();
$res = $zip->open( "update/".$REQ["version"].".zip");
if ($res === TRUE) {
    $zip->extractTo("update/");
    $zip->close();
} else {
    throw new \RuntimeException(sprintf('Could not Unzip file at "%s"', $src));
}
unlink("update/".$REQ["version"].".zip");

$new_files = listFolderFiles("update/MerchantPortal-".$REQ["version"]);
$ignored_files = explode("\n", file_get_contents("update/MerchantPortal-".$REQ["version"]."/.gitignore"));
foreach($ignored_files as $file_to_ignore) {
    if($file_to_ignore == "")
        continue;
    $file_to_ignore = ltrim(rtrim(trim($file_to_ignore, "/"),"/"),"/");
    unset($current_files[$file_to_ignore]);
    unset($new_files[$file_to_ignore]);
}
$current_files = flatten($current_files, $prefix = '');
$new_files = flatten($new_files, $prefix = '');
$files_to_remove = array_diff($current_files, $new_files);

foreach($files_to_remove as $key=>$value) {
    unlink($key);
}

folder_copy("update/MerchantPortal-".$REQ["version"],".");
rmdir("update/MerchantPortal-".$REQ["version"]);

$config = new IPPConfig();
$new_config = $config->UpdateConfig("version",$REQ["version"]);
$config = $config->WriteConfig();

header("Location: /partner/");