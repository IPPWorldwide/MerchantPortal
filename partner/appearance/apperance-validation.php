<?php
include("../b.php");

function recurseRmdir($dir) {
    $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file):
        (is_dir("$dir/$file") && !is_link("$dir/$file")) ? recurseRmdir("$dir/$file") : unlink("$dir/$file");
    endforeach;
    return rmdir($dir);
}
if($_FILES):
    $fileDetail = $_FILES['new-theme']['name'];
    $filed = explode('.',$_FILES['new-theme']['name']);
    $name = $filed[0];
    $ext  = $filed[1];
    if(!file_exists('tmp')):
        mkdir('tmp', 0777, true);
    else:
        array_map('unlink',glob('tmp/*.*'));
        array_map('rmdir',glob('tmp/*'));
    endif;
    $path = 'tmp/'.$fileDetail;
    $isIndex = false;
    $isHeader  = false;
    $isFooter  = false;
    $isValid = 0;
    $response = array(
        "error"=>true,
        "message"=>""
    );
    if(move_uploaded_file($_FILES['new-theme']['tmp_name'],$path)):
        $za = new ZipArchive();
        $za->open($path);
        for( $i = 0; $i < $za->numFiles; $i++ ):
            $stat = $za->statIndex( $i );
            if($stat['name']=='index.php'):
                $isIndex = true;
            endif;
            if($stat['name']=='head.php'):
                $isHeader  = true;
            endif;
            if($stat['name']=='foot.php'):
                $isFooter  = true;
            endif;
        endfor;
        if($isIndex && $isHeader && $isFooter) {
            $isValid = 1;
        }
        switch($isValid):
            case 0:
                $response['message'] = "Theme file is not valid. Required fields is missing in theme.";
                break;
            case 1:
                mkdir('tmp/'.$name, 0777, true);
                $za->extractTo('tmp/'.$name);
                $file = 'tmp/'.$name.'/head.php';
                $fp = fopen($file, 'r');
                $class = $buffer = '';
                $i = 0;
                $za->extractTo('../../theme/'.$name);
                $response['error']   = false;
                $response['message'] = "$name theme uploaded successfully";
                break;
        endswitch;
    else:
        $response['message'] = "File is not uploaded in your path.";
    endif;
else:
    $response['message'] = "Please choose a file";
endif;
$za->close();
recurseRmdir('tmp');
if(file_exists($path))
    unlink($path);
echo json_encode($response);