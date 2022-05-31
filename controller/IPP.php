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

    public function getSession() {
        return ["user_id" => $this->user_id, "session_id" => $this->session_id];
    }

    public function login($username,$password) {
        $data = ["username" => $username, "password" => $password];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/login/", "POST", [], $data);
    }

    public function CheckLogin() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/data/", "POST", [], $data);
    }

    public function SubscriptionsList($result = "ALL") {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id, "result" => $result];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/cards/stored/", "POST", [], $data)->content;
    }

    public function TransactionsList($list_type = "ALL", $result = "ALL") {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id, "type" => $list_type, "result" => $result];
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
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/data/", "POST", [], $data)->content;
    }
    public function MerchantDataUpdate($all_data = []) {
        $security_data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        $security_data["id"] = $all_data["id"];
        $security_data["field"] = "security";
        $security_data["value"] = $all_data["security"];
        $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/data/update", "POST", [], $security_data);

        $meta_data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        $meta_data["id"] = $all_data["id"];
        $meta_data["field"] = "meta";
        $meta_data["value"] = $all_data["meta"];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/data/update.php", "POST", [], $meta_data)->content;
    }
    public function MerchantAcquirerUpdate($acquirer_id,$settings = []) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        $data["acquirer_id"] = $acquirer_id;
        $data["settings"] = $settings;
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/acquirer/data/update.php", "POST", [], $data)->content;
    }

    public function SendPaymentLink($sender,$recipient,$expiry_time,$order_id,$amount,$currency) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        $data["url"]        = $_ENV["PORTAL_URL"];
        $data["sender"]        = $sender;
        $data["recipient"]        = $recipient;
        $data["expiry_time"]        = strtotime($expiry_time);
        $data["order_id"]        = $order_id;
        $data["amount"]        = $amount;
        $data["currency"]        = $currency;
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/payments/links/create/", "POST", [], $data);
    }

    public function InvoiceData($invoice_id) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id,"id" => $invoice_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/invoice/", "POST", [], $data)->content;
    }

    public function AddUser($all_data = []) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        $data = array_merge($all_data, $data);
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/users/add/", "POST", [], $data);
    }
    public function CloseUser($update_user_id) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id, "update_user_id" => $update_user_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/users/close/", "POST", [], $data);
    }
    public function ResetUserPassword($update_user_id,$password) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id, "update_user_id" => $update_user_id, "password" => $password];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/users/password/reset/", "POST", [], $data);
    }
    public function UserData($merchant_id) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id,"company_id" => $merchant_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/users/data/", "POST", [], $data)->content;
    }

    public function DisputesData($dispute_id) {
        $data = ["dispute_id" => $dispute_id,"user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/payments/disputes/data/", "POST", [], $data)->content;
    }
    public function DisputesRelated($transaction_id) {
        $data = ["transaction_id" => $transaction_id,"user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/payments/related/", "POST", [], $data)->content;
    }

    public function ListPayouts() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/payouts/list/", "POST", [], $data)->content;
    }
    public function ListDisputes($state = "ALL", $status = "ALL") {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id, "state" => $state, "status" => $status];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/payments/disputes/list/", "POST", [], $data)->content;
    }
    public function ListUsers() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/users/list/", "POST", [], $data)->content;
    }
    public function ListInvoices() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/invoice/list/", "POST", [], $data)->content;
    }
    public function version() {
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/version.php");
    }
}
