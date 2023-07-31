<?php

class IPPUtils
{
    public function rrmdir($dir) {
        $dir = BASEDIR . $dir;
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }
        rmdir($dir);
    }
    public function rrfile($file) {
        unlink(BASEDIR . $file);
    }
    public function in_object($value,$object) {
        if (is_object($object)) {
            foreach($object as $key => $item) {
                if ($value==$key) return "true";
            }
        }
        return "false";
    }
    public function prefered_language(array $available_languages, $http_accept_language,$standard_langauge = "en-gb") {
        $langs = [];
        $available_languages = array_flip($available_languages);

        preg_match_all('~([\w-]+)(?:[^,\d]+([\d.]+))?~', strtolower($http_accept_language), $matches, PREG_SET_ORDER);
        foreach($matches as $match) {

            list($a, $b) = explode('-', $match[1]) + array('', '');
            $value = isset($match[2]) ? (float) $match[2] : 1.0;

            if(isset($available_languages[$match[1]])) {
                $langs[$match[1]] = $value;
                continue;
            }

            if(isset($available_languages[$a])) {
                $langs[$a] = $value - 0.1;
            }

        }
        if(isset($langs["da"])) {
            $langs["da-dk"] = $langs["da-dk"] ?? $langs["da"];
            unset($langs["da"]);
        }
        if(isset($langs["en"])) {
            $langs["en-gb"] = $langs["en-gb"] ?? $langs["en"];
            unset($langs["en"]);
        }
        if(count($langs) === 0) {
            $langs[$standard_langauge] = "1.0";
        }
        arsort($langs);
        return $langs;
    }

    public function getTimezoneBasedOnOffsetMinutes($offset) {
        $tz = timezone_name_from_abbr('', $offset*60, 1);
        if($tz === false) $tz = timezone_name_from_abbr('', $offset, 0);
        return $tz;
    }

    public function isJson($string) {
        $result = json_decode($string);
        if(json_last_error() === JSON_ERROR_NONE)
            return true;
        else
            return false;

    }
    public function number($numeric,$decimals) {
        return number_format($numeric,$decimals,",",".");
    }
    public function cpy($source, $dest){
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
    public function recurseRmdir($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file):
            (is_dir("$dir/$file") && !is_link("$dir/$file")) ? recurseRmdir("$dir/$file") : unlink("$dir/$file");
        endforeach;
        return rmdir($dir);
    }
    public function createZip($zipArchive, $folder, $new_folder)
    {
        if (is_dir($folder)) {
            if ($f = opendir($folder)) {
                while (($file = readdir($f)) !== false) {
                    if (is_file($folder . $file)) {
                        if ($file != '' && $file != '.' && $file != '..') {
                            $zipArchive->addFile($folder . $file, $new_folder . "/" . $file);
                        }
                    }
                    else {
                        if (is_dir($folder . $file)) {
                            if ($file != '' && $file != '.' && $file != '..') {
                                $zipArchive->addEmptyDir($new_folder . "/" . $file);
                                $read_folder = $folder . $file . '/';
                                $read_new_folder = $new_folder . "/".$file;
                                $this->createZip($zipArchive, $read_folder, $read_new_folder);
                            }
                        }
                    }
                }
                closedir($f);
            } else {
                exit("Unable to open directory " . $folder);
            }
        } else {
            exit($folder . " is not a directory.");
        }
    }

}
