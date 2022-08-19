<?php
include("../base.php");
$merchant_data = $ipp->MerchantData();
$load_script[] = $_ENV["ONBOARDING_BASE_URL"]."/onboarding.api.js?company_id=".$merchant_data->id."&api_key=".$merchant_data->security->key1;
echo head();
echo '
      <h2>'.$lang["COMPANY"]["ONBOARDING"]["HEADER"].'</h2>
        <div id="ipponboarding"></div>
';
foreach($plugins->hook_onboarding as $value) {
    $inline_script[] = "onboarding_extensions.push('".$IPP_CONFIG["PORTAL_URL"]."/plugins/" . $value . "/onboarding.php');";
}
echo foot();