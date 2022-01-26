<?php
class IPP {

    private $user_id;
    private $session_id;
    private $request;

    function __construct($request,$id = "",$session_id = "") {
        $this->request = $request;
        if($id != "")
            $this->user_id = $id;
        if($session_id != "")
            $this->session_id = $session_id;
    }

    public function login($username,$password) {
        $data = ["username" => $username, "password" => $password];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/login/", "POST", [], $data);
    }

    public function CheckLogin() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/data/", "POST", [], $data);
    }

    public function TransactionsList() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/payments/list/", "POST", [], $data)->content;
    }
    public function TransactionsData($action_id) {
        $data = ["action_id" => $action_id,"user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/payments/", "POST", [], $data)->content;
    }
    public function TransactionsRelated($transaction_id) {
        $data = ["transaction_id" => $transaction_id,"user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/payments/related/", "POST", [], $data)->content;
    }
    public function Charts() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/charts/", "POST", [], $data)->content;
    }


    public function MerchantData($data = []) {
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/data/", "POST", [], $data)->content;
    }
    public function MerchantDataUpdate($all_data = []) {
        $security_data = [];
        $security_data["id"] = $all_data["id"];
        $security_data["field"] = "security";
        $security_data["value"] = $all_data["security"];
        $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/data/update", "POST", [], $security_data);

        $meta_data = [];
        $meta_data["id"] = $all_data["id"];
        $meta_data["field"] = "meta";
        $meta_data["value"] = $all_data["meta"];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/data/update", "POST", [], $meta_data)->content;
    }

    public function version() {
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/version.php");
    }
}
