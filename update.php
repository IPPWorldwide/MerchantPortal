<?php
$public_page=1;
include "base.php";
function listFolderFiles($dir)
{
    $fileInfo     = scandir($dir);
    $allFileLists = [];
    foreach ($fileInfo as $folder):
        if ($folder !== '.' && $folder !== '..'):
            if (is_dir($dir . DIRECTORY_SEPARATOR . $folder) === true):
                $allFileLists[$folder] = listFolderFiles($dir . DIRECTORY_SEPARATOR . $folder);
            else:
                $allFileLists[$folder] = $folder;
            endif;
        endif;
    endforeach;
    return $allFileLists;
}

function flatten($array, $prefix = '') {
    $result = array();
    foreach($array as $key=>$value):
        if(is_array($value)):
            $result = $result + flatten($value, $prefix . $key . '/');
        else:
            $result[$prefix . $key] = $value;
        endif;
    endforeach;
    return $result;
}

$current_files = listFolderFiles('.');

file_put_contents("update/".$REQ["version"].".zip", fopen("https://github.com/IPPWorldwide/MerchantPortal/archive/refs/tags/".$REQ["version"].".zip", 'r'));

$zip = new ZipArchive();
$res = $zip->open( "update/".$REQ["version"].".zip");
if ($res === TRUE):
    $zip->extractTo("update/");
    $zip->close();
else:
    throw new \RuntimeException(sprintf('Could not Unzip file at "%s"', $src));
endif;
unlink("update/".$REQ["version"].".zip");

$new_files = listFolderFiles("update/MerchantPortal-".$REQ["version"]);
$ignored_files = explode("\n", file_get_contents("update/MerchantPortal-".$REQ["version"]."/.gitignore"));
foreach($ignored_files as $file_to_ignore):
    if($file_to_ignore == "")
        continue;
    $file_to_ignore = ltrim(rtrim(trim($file_to_ignore, "/"),"/"),"/");
    unset($current_files[$file_to_ignore]);
    unset($new_files[$file_to_ignore]);
endforeach;
$current_files = flatten($current_files, $prefix = '');
$new_files = flatten($new_files, $prefix = '');
$files_to_remove = array_diff($current_files, $new_files);

foreach($files_to_remove as $key=>$value):
    unlink($key);
endforeach;
$utils->cpy("update/MerchantPortal-".$REQ["version"],".");
$utils->recurseRmdir("update/MerchantPortal-".$REQ["version"]);

include("controller/IPPConfig.php");
$config = new IPPConfig();
$new_config = $config->UpdateConfig("version",$REQ["version"]);
$config = $config->WriteConfig();

?>
<script>window.location.href="/partner";</script>
