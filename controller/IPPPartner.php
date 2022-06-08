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

    public function PartnerData() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/data/", "POST", [], $data)->content;
    }

    public function UpdateData($all_data = []) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id, "value" => $all_data, "name" => $all_data["meta"]["name"]];
        $data = array_merge($all_data, $data);
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/data/update/", "POST", [], $data)->content;
    }


    public function AddMerchant($all_data = []) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        $data = array_merge($all_data, $data);
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/company/add/", "POST", [], $data);
    }
    public function CloseMerchant($company_id) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id, "company_id" => $company_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/company/close/", "POST", [], $data);
    }
    public function ResetMerchantPassword($company_id,$company_user_id,$password) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id, "company_id" => $company_id, "company_user_id" => $company_user_id, "password" => $password];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/company/password/reset/", "POST", [], $data);
    }
    public function MerchantData($merchant_id) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id,"company_id" => $merchant_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/company/data/", "POST", [], $data)->content;
    }
    public function MerchantDataUpdate($all_data = []) {
        if(isset($all_data["pos_device"])) {
            $pos = [];
            $pos["user_id"] = $this->user_id;
            $pos["session_id"] = $this->session_id;
            $pos["company_id"] = $all_data["id"];
            $pos["devices"] = $all_data["pos_device"];
            $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/company/data/pos/update/", "POST", [], $pos);
        }
        $security_data = [];
        $security_data["user_id"] = $this->user_id;
        $security_data["session_id"] = $this->session_id;
        $security_data["company_id"] = $all_data["id"];
        $security_data["field"] = "security";
        $security_data["value"] = $all_data["security"];
        $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/company/data/update", "POST", [], $security_data);

        $meta_data = [];
        $meta_data["user_id"] = $this->user_id;
        $meta_data["session_id"] = $this->session_id;
        $meta_data["company_id"] = $all_data["id"];
        $meta_data["field"] = "meta";
        $meta_data["value"] = $all_data["meta"];
        $meta_data["subscription_plan"] = $all_data["subscription_plan"];
        $meta_data["mcc"] = $all_data["mcc"];
        if(isset($all_data["rules"]))
            $meta_data["rules"] = $all_data["rules"];

        $meta_data["acquirers"] = $all_data["acquirers"] ?? [];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/company/data/update/", "POST", [], $meta_data)->content;
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

    public function AddUser($all_data = []) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        $data = array_merge($all_data, $data);
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/users/add/", "POST", [], $data);
    }
    public function CloseUser($update_user_id) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id, "update_user_id" => $update_user_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/users/close/", "POST", [], $data);
    }
    public function ResetUserPassword($update_user_id,$password) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id, "update_user_id" => $update_user_id, "password" => $password];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/users/password/reset/", "POST", [], $data);
    }
    public function UserData($merchant_id) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id,"company_id" => $merchant_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/users/data/", "POST", [], $data)->content;
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

    public function AddInvoice($all_data = []) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        $data = array_merge($all_data, $data);
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/company/invoice/add/", "POST", [], $data);
    }
    public function InvoiceData($invoice_id) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id,"id" => $invoice_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/company/invoice/data/", "POST", [], $data)->content;
    }

    public function AddCommunicationTemplate($hook,$type,$title,$content,$receiver,$active) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id, "hook" => $hook, "type" => $type, "title"=> $title, "content" => $content, "receiver" => $receiver,"active" => $active];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/communication/templates/add/", "POST", [], $data);
    }
    public function UpdateCommunicationTemplate($template_id,$hook,$type,$title,$content,$receiver,$active) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id, "template_id" => $template_id, "hook" => $hook, "type" => $type, "title"=> $title, "content" => $content, "receiver" => $receiver, "active" => $active];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/communication/templates/update/", "POST", [], $data);
    }
    public function CommuncationTemplateData($template_id) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id,"template_id" => $template_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/communication/templates/data/", "POST", [], $data)->content;
    }
    public function CloseCommuncationTemplate($template_id) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id, "template_id" => $template_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/communication/templates/close/", "POST", [], $data);
    }
    public function CommunicationTemplateCopyMissing() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/communication/templates/standard/", "POST", [], $data);
    }



    public function RemovePlugin($id,$slug) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id,"plugin_id"=>$id,"plugin_slug"=>$slug];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/plugins/close/", "POST", [], $data);
    }
    public function InstallPlugin($slug) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id,"plugin_slug"=>$slug];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/plugins/add/", "POST", [], $data)->content;
    }
    public function UpdatePluginSettings($plugin_id,$key,$value) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id,"plugin_id"=>$plugin_id,"key" => $key,"value"=>$value];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/plugins/update/", "POST", [], $data);
    }

    public function ListCountry() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/list/country.php", "POST", [], $data)->content;
    }
    public function ListCompany() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/list/company.php", "POST", [], $data)->content;
    }
    public function ListCompanyInvoices($company_id) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id,"company_id" => $company_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/invoices/company.php", "POST", [], $data)->content;
    }
    public function ListPOSDevices($company_id) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id,"company_id" => $company_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/list/pos_devices.php", "POST", [], $data)->content;
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
    public function ListTemplates() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/list/communication.php", "POST", [], $data)->content;
    }

    public function ListTypes() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/list/types.php", "POST", [], $data)->content;
    }
    public function ListUsers() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/partner/list/users.php", "POST", [], $data)->content;
    }
    public function ListPlugins() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/plugins/list.json", "POST", [], $data);
    }
}
