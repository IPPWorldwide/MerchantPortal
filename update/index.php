<?php
$public_page=1;
include "../base.php";
$updated_version = [];
$original_version = [];
function recurse_copy($src,$replaced_value,$ignored_files) {
    $array = [];
    $dir = opendir($src);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            $true_url = ltrim(str_replace($replaced_value,"", $src) . '/' . $file,"\/");
            if(in_array($true_url,$ignored_files,false)) {
                continue;
            }
            if(in_array($src . '/' . $file,$ignored_files))
                continue;
            if ( is_dir($src . '/' . $file)) {
                $array = array_merge($array,recurse_copy($src . '/' . $file,$replaced_value,$ignored_files));
            }
            else {
                $array[] = $true_url;
            }
        }
    }
    closedir($dir);
    return $array;
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

$version = $REQ["version"];
$dirMode = 0777;
$src = "update/";
$dst = "../";
if (!mkdir($src, $dirMode, true) && !is_dir($src)) {
    throw new \RuntimeException(sprintf('Directory "%s" was not created', $src));
}
file_put_contents($src.$REQ["version"].".zip", fopen("https://github.com/IPPWorldwide/MerchantPortal/archive/refs/tags/".$REQ["version"].".zip", 'r'));

$zip = new ZipArchive();
$res = $zip->open($src.$REQ["version"].".zip");
if ($res === TRUE) {
    $zip->extractTo($src);
    $zip->close();
} else {
    throw new \RuntimeException(sprintf('Could not Unzip file at "%s"', $src));
}
unlink($src.$REQ["version"].".zip");

$ignored_files = explode("\n", file_get_contents($src."MerchantPortal-".$REQ["version"]."/.gitignore"));

$updated_version = recurse_copy($src."MerchantPortal-".$REQ["version"],"update/MerchantPortal-0.1.4/",$ignored_files);
$original_version = recurse_copy("../","../",$ignored_files);
$result = array_diff($original_version, $updated_version);
foreach($result as $value) {
    unlink("../".$value);
}
print_r($result);
folder_copy($src."MerchantPortal-".$REQ["version"],$dst);
rmdir($src);
header("Location: /partner/");