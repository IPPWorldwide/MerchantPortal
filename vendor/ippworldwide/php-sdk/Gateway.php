<?php
class IPPGateway {

    private $company_id;
    private $company_key2;

    function __construct($id,$key) {
        $this->company_id = $id;
        $this->company_key2 = $key;
    }

    public function checkout_id($data){
        return $this->curl("https://api.ippeurope.com/payments/checkout_id", "POST", [], $data)->content;
    }
    public function payment_status($transaction_id,$transaction_key){
        $data = ["transaction_id" => $transaction_id, "transaction_key" => $transaction_key];
        return $this->curl("https://api.ippeurope.com/payments/status", "POST", [], $data)->content;
    }
    public function request($url, $data){
        return $this->curl("https://api.ippeurope.com/".$url, "POST", [], $data);
    }
    private function curl($url, $type = 'POST', $query = [], $data = [], $headers = []){
        $data["id"] = $this->company_id;
        $data["key2"] = $this->company_key2;
        $data["origin"] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url.php?".http_build_query($query, "", "&", PHP_QUERY_RFC3986));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        if($type == "POST") {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
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
