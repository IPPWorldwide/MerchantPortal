<?php
include_once("../base.php");
require_once("parts/persons.php");
$merchant_data = $ipp->MerchantData();
if(isset($REQ["vat"])) {
    echo json_encode($ipp->PublicCompanyData($REQ["vat"]));
    die();
}
if(isset($REQ["person"])) {
    $id = $REQ["id"] ?? "";
    $name = $REQ["name"] ?? "";
    $email = $REQ["email"] ?? "";
    $address = $REQ["address"] ?? "";
    $postal = $REQ["postal"] ?? "";
    $city = $REQ["city"] ?? "";
    $country = $REQ["country"] ?? "";
    $files_passport = $REQ["files_passport"] ?? "";
    $files_driving_license_front = $REQ["files_driving_license_front"] ?? "";
    $files_driving_license_back = $REQ["files_driving_license_back"] ?? "";
    $files_address = $REQ["files_address"] ?? "";
    echo html_preson(
        $id, $name, $email, $address,$postal,$city,$country,$files_passport,$files_driving_license_front,$files_driving_license_back,$files_address);
    die();
}
$load_css[] = "onboarding.css";
$load_script[] = "assets/js/jquery.md5.js";
$load_script[] = "assets/js/company.js";
$load_script[] = "assets/js/financial.js";
$load_script[] = "assets/js/website.js";
$load_script[] = "assets/js/contract.js";
echo head();
$onboarding_data = $merchant_data->onboarding_data;
$actions->get_action("onboarding");
echo '
      <h2>'.$lang["COMPANY"]["ONBOARDING"]["HEADER"].'</h2>
';
if(isset($onboarding_data->validated) && isset($onboarding_data->validated->company) && $onboarding_data->validated->company) {
    $inline_script[] = "ChangePage('','contract','contracts_sent');";
}
echo "<div id='onboarding_form' class='row mb-3'>";
    echo "<div class='col-3' id='onboarding_menu'>";
        echo "<ul>";
            echo "<li class='group company' data-group='company'><span data-group='company' data-href='url'>General info</span>";
                echo "<ol>";
                    echo "<li data-href='url'>URL</li>";
                    echo "<li data-href='country'>Country</li>";
                    echo "<li data-href='company_data'>Company data</li>";
                    echo "<li data-href='ubo' disabled='disabled'>UBO & Directors</li>";
                echo "</ol>";
            echo "</li>";
            echo "<li class='group financial' data-group='financial'><span data-group='financial' data-href='settlement'>Financial data</span>";
                echo "<ol>";
                    echo "<li data-href='settlement'>Settlement account</li>";
                    echo "<li data-href='earlier_provider'>Earlier processing</li>";
                echo "</ol>";
            echo "</li>";
            echo "<li class='group website' data-group='website'><span data-group='website' data-href='website_check'>Website</span>";
                echo "<ol>";
                    echo "<li data-href='website_check'>Website Check</li>";
                    echo "<li data-href='mcc'>MCC code</li>";
                    echo "<li data-href='limits'>Purchase limits</li>";
                    echo "<li data-href='timeframes'>Service timeframes</li>";
                echo "</ol>";
            echo "</li>";
            echo "<li class='group contract' data-group='contract' aria-disabled='true'><span data-group='website' data-href='our_contracts'>Contract</span>";
                echo "<ol>";
                    echo "<li data-href='our_contracts'>Our contract</li>";
                echo "</ol>";
            echo "</li>";
        echo "</ul>";
    echo "</div>";
    echo "<div class='col-7 content'>";
        require_once("parts/welcome.php");
        require_once("parts/company.php");
        require_once("parts/financial.php");
        require_once("parts/website.php");
        require_once("parts/contract.php");
    echo "</div>";
echo "</div>";
if(isset($plugins->hook_onboarding)) {
    foreach($plugins->hook_onboarding as $value) {
        $inline_script[] = "onboarding_extensions.push('".$IPP_CONFIG["PORTAL_URL"]."/plugins/" . $value . "/onboarding.php');";
    }
}
echo foot();
