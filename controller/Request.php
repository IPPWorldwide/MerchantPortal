<?php
class IPPRequest {
    private $user_id;
    private $session_id;
    private $key2;

    function __construct(String $user_id,String $session_id) {
        $this->user_id = $user_id;
        $this->session_id = $session_id;
    }

    public function set_key2(String $key2) {
        $this->key2 = $key2;
    }

    public function download(String $url, String $dest, String $fileName) {
        $fr = @fopen($url, 'r');

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
    public function portal($url, $data){
        return $this->curl($_ENV["PORTAL_URL"]."/".$url, "POST", [], $data);
    }

    public function request($url, $data){
        return $this->curl($_ENV["GLOBAL_BASE_URL"]."/".$url, "POST", [], $data);
    }

    public function plugins($url, $data){
        return $this->curl("https://plugins.ippworldwide.com/".$url, "GET", $data);
    }

    public function curl($url, $type = 'POST', $query = [], $data = [], $headers = [],$file=false){
        global $_SESSION,$IPP_CONFIG;
        if(isset($this->user_id) && strlen($this->user_id) > 0)
            $data["user_id"] = $this->user_id;
        if(isset($this->session_id) && strlen($this->session_id) > 0)
            $data["session_id"] = $this->session_id;
        if(isset($this->key2) && strlen($this->key2) > 0)
            $data["key2"] = $this->key2;

        if(isset($_ENV["PARTNER_ID"]))
            $data["partner_id"] = $_ENV["PARTNER_ID"];
        if(isset($_SESSION["ipp_type"]) && $_SESSION["ipp_type"] == "partner") {
            $data["key1"] = $_ENV["PARTNER_KEY1"];
            $data["key2"] = $_ENV["PARTNER_KEY2"];
        }
        if($file) {
            $data["attached_file"] = new CURLFile($file['tmp_name'], $file['type'], $file['name']);
        }
        if(isset($IPP_CONFIG["PARTNER_ID"]))
            $data["partner_id"] = $IPP_CONFIG["PARTNER_ID"];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url?".http_build_query($query, "", "&", PHP_QUERY_RFC3986));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if($type == "POST") {
            curl_setopt($ch, CURLOPT_POST, 1);
            if($file) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            } else
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
        return $server_output;
    }
}
