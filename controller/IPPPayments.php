<?php
class IPPPayments extends IPP {

    private $company_id;
    private $company_key2;
    private $request;

    function __construct($request,$id,$key) {
        $this->request = $request;
        $this->company_id = $id;
        $this->company_key2 = $key;
    }

    public function checkout_id($data){
        return $this->request->curl("https://api.ippworldwide.com/payments/checkout_id", "POST", [], $data)->content->checkout_id;
    }
    public function payment_status($transaction_id,$transaction_key){
        $data = ["transaction_id" => $transaction_id, "transaction_key" => $transaction_key];
        return $this->request->curl("https://api.ippworldwide.com/payments/status", "POST", [], $data)->content;
    }
    public function request($url, $data){
        return $this->request->curl("https://api.ippworldwide.com/".$url, "POST", [], $data);
    }

}
