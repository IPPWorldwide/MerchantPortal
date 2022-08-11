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

}