<?php
include("../base.php");
$merchant_data = $ipp->MerchantData();
$load_script[] = $_ENV["ONBOARDING_BASE_URL"]."/onboarding.api.js?company_id=".$merchant_data->id."&api_key=".$merchant_data->security->key1;
echo head();
$actions->get_action("onboarding");
echo '
      <h2>'.$lang["COMPANY"]["ONBOARDING"]["HEADER"].'</h2>
        <div id="ipponboarding"></div>
';
if(isset($plugins->hook_onboarding)) {
    foreach($plugins->hook_onboarding as $value) {
        $inline_script[] = "onboarding_extensions.push('".$IPP_CONFIG["PORTAL_URL"]."/plugins/" . $value . "/onboarding.php');";
    }
}
echo foot();
