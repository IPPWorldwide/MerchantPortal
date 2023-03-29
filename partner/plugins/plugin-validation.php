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
$fileDetail = $_FILES['new-plugin']['name'];
$filed = explode('.',$_FILES['new-plugin']['name']);
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
$isInit  = false;
$isValid = 0;
$response = array(
    "error"=>true,
    "message"=>""
);
if(move_uploaded_file($_FILES['new-plugin']['tmp_name'],$path)):
    $za = new ZipArchive();
    $za->open($path);
    for( $i = 0; $i < $za->numFiles; $i++ ):
        $stat = $za->statIndex( $i );
        if($stat['name']=='index.php'):
            $isIndex = true;
        endif;
        if($stat['name']=='init.php'):
            $isInit  = true;
        endif;
    endfor;
    if(!$isIndex):
        $isValid = 1;
    endif;
    if(!$isInit):
        $isValid = 2;
    endif;
    switch($isValid):
        case 0:
            mkdir('tmp/'.$name, 0777, true);
            $za->extractTo('tmp/'.$name);
            $file = 'tmp/'.$name.'/init.php';
            $fp = fopen($file, 'r');
            $class = $buffer = '';
            $i = 0;
            $classes = [];
            $extends = [];
            while (!$class) {
                if (feof($fp)) break;

                $buffer .= fread($fp, 512);
                $tokens = token_get_all($buffer);

                if (strpos($buffer, '{') === false) continue;

                for (;$i<count($tokens);$i++) {
                    if ($tokens[$i][0] === T_CLASS) {
                        for ($j=$i+1;$j<count($tokens);$j++) {
                            if ($tokens[$j] === '{') {
                                $class = $tokens[$i+2][1];
                                array_push($classes,$class);
                            }
                        }
                    }

                    // class [class]
                    if ($tokens[$i][0] == T_EXTENDS) {
                        $class =  $tokens[$i+2][1];
                        array_push($extends,$class);
                    }
                }
            }
                if($classes[0] == $name):
                    if(count($extends)==1 && $extends[0] == 'IPPPlugins'):
                        $za->extractTo('../../plugins/'.$name);
                        $partner->InstallPlugin($name);
                        $response['error']   = false;
                        $response['message'] = "$name plugin uploaded successfully";
                    else:
                        $response['message'] = "Class should be extends  IPPPlugins Class";
                    endif;
                else:
                    $response['message'] = "Class name exactly same as .zip file name";
                endif;
            break;
        case 1:
            $response['message'] = "Zip file is not valid 'index.php' file is missing in zip folder.";
            break;
        case 2:
            $response['message'] = "Zip file is not valid 'init.php' file is missing in zip folder.";
            break;
    endswitch;
    $za->close();
else:
    $response['message'] = "File is not uploaded in your path.";
endif;
else:
    $response['message'] = "Please choose a file";
endif;
recurseRmdir('tmp');
if(file_exists($path))
    unlink($path);
echo json_encode($response);
