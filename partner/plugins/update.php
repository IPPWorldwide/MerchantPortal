<?php
include("../b.php");
if(!class_exists("cpy")) {
    function cpy($source, $dest){
        if(is_dir($source)):
            $dir_handle=opendir($source);
            while($file=readdir($dir_handle)):
                if($file!="." && $file!=".."):
                    if(is_dir($source."/".$file)):
                        if(!is_dir($dest."/".$file)):
                            mkdir($dest."/".$file);
                        endif;
                        cpy($source."/".$file, $dest."/".$file);
                    else:
                        copy($source."/".$file, $dest."/".$file);
                    endif;
                endif;
            endwhile;
            closedir($dir_handle);
        else:
            copy($source, $dest);
        endif;
    }
}
if(!class_exists("recurseRmdir")) {
    function recurseRmdir($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file):
            (is_dir("$dir/$file") && !is_link("$dir/$file")) ? recurseRmdir("$dir/$file") : unlink("$dir/$file");
        endforeach;
        return rmdir($dir);
    }
}

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