<?php
include("../base.php");
$merchant_data = $ipp->MerchantData();
$load_css[] = "onboarding.css";
$load_css[] = "css/flag-icons.min.css";
//$load_script[] = $_ENV["ONBOARDING_BASE_URL"]."/onboarding.api.js?company_id=".$merchant_data->id."&api_key=".$merchant_data->security->key1;
echo head();
$actions->get_action("onboarding");
echo '
      <h2>'.$lang["COMPANY"]["ONBOARDING"]["HEADER"].'</h2>
';

echo "<div id='onboarding_form' class='row mb-3'>";
    echo "<div class='col-3' id='onboarding_menu'>";
        echo "<ul>";
            echo "<li class='group company' data-group='company' data-href='url'><span>General info</span>";
                echo "<ol>";
                    echo "<li data-href='url'>URL</li>";
                    echo "<li data-href='country'>Country</li>";
                    echo "<li data-href='company_data'>Company data</li>";
                    echo "<li data-href='ubo'>UBO & Directors</li>";
                echo "</ol>";
            echo "</li>";
            echo "<li class='group financial' data-group='financial'><span>Financial data</span>";
                echo "<ol>";
                    echo "<li>Settlement account</li>";
                    echo "<li>Settlement frequency</li>";
                    echo "<li>Earlier processing</li>";
                echo "</ol>";
            echo "</li>";
            echo "<li class='group website' data-group='website'><span>Website</span>";
                echo "<ol>";
                    echo "<li>Website Check</li>";
                    echo "<li>MCC code</li>";
                    echo "<li>Purchase limits</li>";
                    echo "<li>Service timeframes</li>";
                echo "</ol>";
            echo "</li>";
            echo "<li class='group contract' data-group='contract' aria-disabled='true'><span>Contract</span>";
                echo "<ol>";
                    echo "<li>Our contract</li>";
                    echo "<li>POA Contract</li>";
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

/*
if(isset($plugins->hook_onboarding)) {
    foreach($plugins->hook_onboarding as $value) {
        $inline_script[] = "onboarding_extensions.push('".$IPP_CONFIG["PORTAL_URL"]."/plugins/" . $value . "/onboarding.php');";
    }
}
*/
echo foot();
