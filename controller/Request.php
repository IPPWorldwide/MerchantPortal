<?php
class IPPRequest {
    function __construct() {

    }

    public function download($url, $dest, $fileName) {

        $fr = @fopen($url, 'r');
        if ($fr === false) {
            throw new Primage_Proxy_Storage_SourceNotFound($url);
        }

        $fw = fopen($dest, 'w');
        if ($fw === false) {
            throw new Exception('Writing to file "' . $dest . '" failed');
        }

        $deadline = time() + 5000;

        while(!feof($fr)) {
            $bufferString = fread($fr, 10000);
            fwrite($fw, $bufferString);
            if ($deadline - time() < 10) {
                fclose($fw);
                fclose($fr);
            }
        }
        fclose($fw);
        fclose($fr);
    }

    public function request($url, $data){
        return $this->curl($_ENV["GLOBAL_BASE_URL"]."/".$url, "POST", [], $data);
    }

    public function curl($url, $type = 'POST', $query = [], $data = [], $headers = []){
        global $_SESSION;
        if(isset($this->user_id) && $this->user_id != "")
            $data["user_id"] = $this->user_id;
        if(isset($this->session_id) && $this->session_id != "")
            $data["session_id"] = $this->session_id;

        $data["partner_id"] = $_ENV["partner_id"];
        if(isset($_SESSION["ipp_type"]) && $_SESSION["ipp_type"] == "partner") {
            $data["key1"] = $_ENV["partner_key1"];
            $data["key2"] = $_ENV["partner_key2"];
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url?".http_build_query($query, "", "&", PHP_QUERY_RFC3986));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if($type == "POST") {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        if (is_array($headers) && sizeof($headers) > 0) {
            curl_setopt($ch, CURLOPT_HEADER, $headers);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        }
        $server_output = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($server_output);
        if (json_last_error() == JSON_ERROR_NONE) {
            return $json;
        }
        return $json;
    }
}
