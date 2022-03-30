<?php
include("../base.php");
$merchant_data = $ipp->MerchantData();
$load_script[] = $_ENV["ONBOARDING_BASE_URL"]."/onboarding.api.js?company_id=".$merchant_data->id."&api_key=".$merchant_data->security->key1;
echo head();
?>
      <h2>Merchant Onboarding</h2>
        <div id="ipponboarding"></div>
<?php
echo foot();