<?php
include("../b.php");
echo head();
$onboarding_status = $REQ["onboarding_status"] ?? "WAITING_APPROVAL";
$onboarding_list = $partner->ListOnboardings($onboarding_status);
var_dump($onboarding_list);
echo '
<h2>Merchant Onboarding</h2>
<form action="?" method="GET">
    <div class="form-group">
        <select id="onboarding_status" name="payment_type">
            <option value="WAITING_APPROVAL"'; if($onboarding_status === "WAITING_APPROVAL") { echo " selected"; } echo '>'.$lang["PARTNER"]["ONBOARDING"]["WAITING_FOR_APPROVAL"].'</option>
            <option value="WAITING"'; if($onboarding_status === "WAITING") { echo " selected"; } echo '>'.$lang["PARTNER"]["ONBOARDING"]["WAITING_FOR_MERCHANT"].'</option>
            <option value="APPROVED"'; if($onboarding_status === "APPROVED") { echo " selected"; } echo '>'.$lang["PARTNER"]["ONBOARDING"]["APPROVED"].'</option>
        </select>
    </div>
    <input type="submit" value="'.$lang["PARTNER"]["ONBOARDING"]["CHANGE_VIEW"].'" class="btn btn-primary">
</form>
';
            echo '
<div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th scope="col">'.$lang["PARTNER"]["ONBOARDING"]["COMPANY_ID"].'</th>
              <th scope="col">'.$lang["PARTNER"]["ONBOARDING"]["COMPANY_NAME"].'</th>
              <th scope="col">'.$lang["PARTNER"]["ONBOARDING"]["KEY_STAKEHOLDERS"].'</th>
              <th scope="col">'.$lang["PARTNER"]["ONBOARDING"]["LATEST_CHANGE_BY_MERCHANT"].'</th>
              <th scope="col">'.$lang["PARTNER"]["ONBOARDING"]["APPROVED_MERCHANT"].'</th>
              <th scope="col">'.$lang["PARTNER"]["ONBOARDING"]["APPROVED_PARTNER"].'</th>
              <th scope="col">'.$lang["PARTNER"]["ONBOARDING"]["APPROVED_ACQUIRER"].'</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
          </table>
</div>';
echo foot();