<?php
class IPPPartner {

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
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/login/", "POST", [], $data);
    }

    public function CheckLogin() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/data/", "POST", [], $data);
    }

    public function PartnerData($data = []) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/data/", "POST", [], $data)->content;
    }

    public function MerchantData($merchant_id) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id,"company_id" => $merchant_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/company/data/", "POST", [], $data)->content;
    }
    public function MerchantDataUpdate($all_data = []) {
        $security_data = [];
        $security_data["user_id"] = $this->user_id;
        $security_data["session_id"] = $this->session_id;
        $security_data["company_id"] = $all_data["id"];
        $security_data["field"] = "security";
        $security_data["value"] = $all_data["security"];
        $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/company/data/update.php", "POST", [], $security_data);

        $meta_data = [];
        $meta_data["user_id"] = $this->user_id;
        $meta_data["session_id"] = $this->session_id;
        $meta_data["company_id"] = $all_data["id"];
        $meta_data["field"] = "meta";
        $meta_data["value"] = $all_data["meta"];
        $meta_data["subscription_plan"] = $all_data["subscription_plan"];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/company/data/update.php", "POST", [], $meta_data)->content;
    }

    public function ValidateDomains($domains) {
        $github_data = $data = [
            "user_id" => $this->user_id,
            "session_id" => $this->session_id
        ];
        foreach($domains["domain"] as $key=>$value) {
            $data[$key] = $value;
        }
        foreach($domains["github"] as $key=>$value) {
            $github_data["domain_id"]       = $key;
            $github_data["github_username"] = $value["username"];
            $github_data["github_password"] = $value["token"];
            $github_data["repo"]            = $value["domain"];
            $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/domain/github/", "POST", [], $github_data);
        }
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/domain/verify/", "POST", [], $data);
    }
    public function UpgradePortal() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        $data["domain_id"] = $_ENV["domain_id"];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/portals/update.php", "POST", [], $data);
    }



    public function AddSubscriptionPlan($all_data = []) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        $data = array_merge($all_data, $data);
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/subscription_plans/add.php", "POST", [], $data);
    }
    public function UpdateSubscriptionPlan($all_data = []) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        $data = array_merge($all_data, $data);
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/subscription_plans/update.php", "POST", [], $data);
    }
    public function SubscriptionPlanData($id) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id,"id"=>$id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/subscription_plans/", "POST", [], $data)->content;
    }



    public function ListCompany() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/list/company.php", "POST", [], $data)->content;
    }
    public function ListCompanyInvoices($company_id) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id,"company_id" => $company_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/invoices/company.php", "POST", [], $data)->content;
    }
    public function ListAcquirers() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/list/acquirers.php", "POST", [], $data)->content;
    }
    public function ListAcquirersFields() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/list/acquirers_fields.php", "POST", [], $data)->content;
    }
    public function ListPartner() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/list/partner.php", "POST", [], $data)->content;
    }
    public function ListSubscriptionPlans() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/list/subscription_plans.php", "POST", [], $data)->content;
    }
    public function Listinvoices() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/list/invoices.php", "POST", [], $data)->content;
    }

    public function ListTypes() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/list/types.php", "POST", [], $data)->content;
    }
    public function ListUsers() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/list/users.php", "POST", [], $data)->content;
    }
}
