<?php
class IPPConfig {

    public function UpdateConfig($key,$value) {
        global $IPP_CONFIG;

        if(is_string($key)) {
            $IPP_CONFIG[$key] = $value;
        }
        return $IPP_CONFIG;
    }
    public function WriteConfig(): void
    {
        global $IPP_CONFIG;
        $string = "<?php\n";
        foreach($IPP_CONFIG as $key=>$value) {
            $value = str_replace('"','\"',$value);
            $string .= "\$IPP_CONFIG[\"$key\"] = \"$value\";\n";
        }
        $myfile = fopen(BASEDIR . "ipp-config.php", "w") or die("Unable to update config file with new version!");
        fwrite($myfile, $string);
        fclose($myfile);
    }

}
