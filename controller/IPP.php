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
        $login_response = $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/login/", "POST", [], $data);
        if(isset($login_response->content->user_id)) {
            $this->user_id = $login_response->content->user_id;
            $this->session_id = $login_response->content->session_id;
        }
        return $login_response;
    }

    public function CheckLogin() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/data/", "POST", [], $data);
    }

    public function SubscriptionsList($result = "ALL") {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id, "result" => $result];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/cards/stored/", "POST", [], $data)->content;
    }

    public function TransactionsList($list_type,$result,$payment_start,$payment_end) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id, "type" => $list_type, "result" => $result,"payment_earliest" => (strtotime($payment_start)-$_COOKIE["timezone"]),"payment_latest"=>(strtotime($payment_end)-$_COOKIE["timezone"])];
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
    public function TransactionsAction($action,$transaction_id,$action_id,$amount = 0) {
        global $IPP_CONFIG;
        if((isset($IPP_CONFIG["PORTAL_LOCAL_DEACTIVATE_VOID"]) && $IPP_CONFIG["PORTAL_LOCAL_DEACTIVATE_VOID"] === "1" && $action === "void") || (isset($IPP_CONFIG["PORTAL_LOCAL_DEACTIVATE_REFUND"]) && $IPP_CONFIG["PORTAL_LOCAL_DEACTIVATE_REFUND"] === "1" && $action === "refund"))
            return false;
        $data = ["action" => $action,"transaction_id" => $transaction_id,"action_id"=>$action_id,"amount" => $amount,"user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/payments/$action/", "POST", [], $data);
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
    public function ChangeUserAccessRight($update_user_id, $access_right) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id,"update_user_id"=>$update_user_id,"rule" => $access_right];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/users/update/access_policy/", "POST", [], $data);
    }
    public function UserData($merchant_id) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id,"company_id" => $merchant_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/users/data/", "POST", [], $data)->content;
    }
    public function RequestResetUserPassword($partner_id,$email, $portal) {
        $data = ["partner_id" => $partner_id,"email" => $email,"portal" => $portal];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/users/password/request/", "POST", [], $data);
    }
    public function ConfirmResetUserPassword($partner_id,$user_id,$initialization_time,$hash) {
        $data = ["partner_id" => $partner_id,"user_id" => $user_id,"initialization_time" => $initialization_time,"hash" => $hash];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/users/password/request/confirm.php", "POST", [], $data);
    }

    public function DisputesData($dispute_id) {
        $data = ["dispute_id" => $dispute_id,"user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/payments/disputes/data/", "POST", [], $data)->content;
    }
    public function DisputesUpload($dispute_id,$type,$file) {
        $data = ["dispute_id" => $dispute_id,"user_id" => $this->user_id, "session_id" => $this->session_id,"type" => $type];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/payments/disputes/upload/", "POST", [], $data, [],$file)->content;
    }
    public function DisputesRelated($transaction_id) {
        $data = ["transaction_id" => $transaction_id,"user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/payments/disputes/related/", "POST", [], $data)->content;
    }

    public function Search($search_term) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id,"search" => $search_term];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/search/", "POST", [], $data)->content;
    }

    public function InstallPlugin($company_id,$slug,$key1="") {
        if($key1 === "")
            $data = ["user_id" => $this->user_id, "session_id" => $this->session_id,"plugin_slug"=>$slug];
        else
            $data = ["company_id" => $company_id, "key1" => $key1,"plugin_slug"=>$slug];
        $install = $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/plugins/add/", "POST", [], $data)->content;
        require_once BASEDIR . "plugins/".$slug."/init.php";
        $new_pugin = new $slug();
        $standard_configs = $new_pugin->getStandardConfigs($slug);
        $std_settings = [];
        foreach($standard_configs as $value)
            $std_settings[$value["name"]] = $value["standard"];

        $myfile = fopen(BASEDIR . "plugins/".$slug."/".$company_id."_settings.php", "w") or die("Unable to open file!");
        $txt = "<?php\n";
        $txt .= "\$settings[\"plugin_id\"] = '" . $install->plugin_id . "';\n";
        foreach($std_settings as $key=>$value) {
            $txt .= "\$settings[\"".$key."\"] = '" . $value . "';\n";
        }
        fwrite($myfile, $txt);
        fclose($myfile);

        if(method_exists($new_pugin,"hookInstallCompany"))
            $new_pugin->hookInstallCompany($install->plugin_id,$this->user_id,$this->session_id);

        return $install;
    }
    public function UpdatePluginSettingFile($ipp,$plugins,$plugin_slug,$company_data,$REQ,$FILES,$clean_value_storage=false) {
        $data_fields = $plugins->available_plugins[$plugin_slug]->getFields();
        $myfile = fopen(BASEDIR . "plugins/".$plugin_slug."/".$company_data->content->id."_settings.php", "w") or die("Unable to open file!");
        $txt = "<?php\n";
        fwrite($myfile, $txt);
        foreach($REQ as $key=>$value) {
            $ipp->UpdatePluginSettings($REQ["plugin_id"],$key,$value);
            if($clean_value_storage)
                $txt = "\$settings[\"".$key."\"] = " . $value . ";\n";
            else
                $txt = "\$settings[\"".$key."\"] = '" . $value . "';\n";
            fwrite($myfile, $txt);
        }
        foreach($data_fields as $value) {
            if(isset($value["type"]) && $value["type"] === "file") {
                if(isset($FILES[$value["id"]]['tmp_name'])) {
                    $file = $FILES[$value["id"]]['tmp_name'];
                    $file_data = base64_encode(file_get_contents($file));
                    if($file_data !== "") {
                        $ipp->UpdatePluginSettings($REQ["plugin_id"],$value["id"],$file_data);
                        fwrite($myfile, "\$settings[\"".$value["id"]."\"] = '" . $file_data . "';\n");
                    }
                }
            }
        }
        fclose($myfile);
        $plugin = new $plugin_slug();
        if(method_exists($plugin,"hookUpdate"))
            $plugin->hookUpdate($plugin_slug,$REQ["plugin_id"],$REQ,$company_data->content->id);
        return json_encode($REQ);
        die();
    }
    public function UpdatePluginSettings($plugin_id,$key,$value) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id,"plugin_id"=>$plugin_id,"key" => $key,"value"=>$value];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/plugins/update/", "POST", [], $data);
    }
    public function RemovePlugin($company_id,$id,$slug) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id,"plugin_id"=>$id,"plugin_slug"=>$slug];
        $remove = $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/plugins/close/", "POST", [], $data)->content;
        
        
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
    public function ListPaymentLinks() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/payments/links/list/", "POST", [], $data)->content;
    }
    public function ListVersions() {
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/versions.php")->content->versions;
    }
    public function ListPlugins() {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/plugins/", "POST", [], $data)->content;
    }

    public function version() {
        if(!isset($_ENV["GLOBAL_BASE_URL"]))
            $_ENV["GLOBAL_BASE_URL"] = "https://api.ippeurope.com";
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/version.php");
    }

    public function GetAllAccessRights()
    {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/users/access_policy/list/", "GET", $data);
    }

    public function PageLevelAccess($check_access)
    {
        $logged_in_data = $this->CheckLogin();
        $all_rules = $this->GetAllAccessRights();

        foreach($logged_in_data->content->user->acccess_rights as $idx=>$right){
            if($right === "ALL" OR $all_rules->content->all_rules->{$right}->name === $check_access){
                return true;
            }
        }
        return false;
    }
    public function AddAccessRight($name, $rules) {
        $data = ["user_id" => $this->user_id, "session_id" => $this->session_id,"name"=>$name,"rules" => $rules];
        return $this->request->curl($_ENV["GLOBAL_BASE_URL"]."/company/users/access_policy/add/", "POST", [], $data);
    }

}
