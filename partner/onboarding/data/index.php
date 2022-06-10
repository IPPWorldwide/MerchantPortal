<?php
include("../../b.php");
$onboarding_data = $partner->OnboardingData($REQ["id"]);

if(isset($REQ["ApproveApplication"]) || isset($REQ["DeclineApplication"])) {
    $state = 0;
    if(isset($REQ["ApproveApplication"]))
        $state = 1;
    $data = (object)$REQ;
    $data->personel = new StdClass;
    $data->personel = $onboarding_data->key_personnel;
    $partner->OnboardingPartnerData($REQ["company_id"],$REQ,$state);
    header("Location: /partner/onboarding/");
    die();
}

echo head();
echo '
<h1>'.$onboarding_data->{"name"}.'</h1>
';
if($onboarding_data->validated->partner) {
    echo "Application have already been approved.";
    die();
}
echo '
<form method="POST" action="?">
<input type="hidden" name="id" value="'.$REQ["id"].'">
<input type="hidden" name="company_id" value="'.$REQ["id"].'">
<div>
    <div class="tab-pane active" id="company" role="tabpanel" aria-labelledby="home-tab">
    <h2>About the company</h2>
        <div class="row row-cols-md-3 mb-3">
            <div class="col themed-grid-col">
            <label for="company-name">Country</label><br>
            <select name="company-country" class="form-control">
            ';
            foreach($partner->ListCountry() as $key=>$value) {
                echo "<option value='".$value->id."' ";
                echo (isset($onboarding_data->data->{"company-country"}) && $onboarding_data->data->{"company-country"} == $value->id) ? "selected" : "";
                echo ">".$value->name."</option>";
            }
            echo '
            </select></div>
            <div class="col themed-grid-col"><label for="company-name">Company Name</label><br><input name="company-name" class="form-control" value="'.$onboarding_data->{"name"}.'"></div>
            <div class="col themed-grid-col"><label for="company-vat">Company Registration number</label><br><input name="company-vat" class="form-control" value="'.$onboarding_data->data->{"company-vat"}.'"></div>
        </div>
        <div class="row row-cols-md-3 mb-3">
            <div class="col themed-grid-col"><label for="company-address">Address</label><br><input name="company-address" class="form-control" value="'.$onboarding_data->data->{"company-address"}.'"></div>
            <div class="col themed-grid-col"><label for="company-postal">Company Postal</label><br><input name="company-postal" class="form-control" value="'.$onboarding_data->data->{"company-postal"}.'"></div>
            <div class="col themed-grid-col"><label for="company-city">Company City</label><br><input name="company-city" class="form-control" value="'.$onboarding_data->data->{"company-city"}.'"></div>
        </div>
        <div class="row row-cols-md-1 mb-1">
            <div class="col themed-grid-col"><h2>What describes best your products</h2></div>
        </div>
        <div class="row row-cols-md-3 mb-3">
            <div class="col themed-grid-col">
                <label for="commercial-ewallet">Select category for what you are selling</label>
                <select name="commercial-mcc" class="form-control OnboardingUpdateFields">';
                foreach($mcc->list() as $key=>$value) {
                    echo "<option value='".$key."' ";
                    echo (isset($onboarding_data->data->{"commercial-mcc"}) && $onboarding_data->data->{"commercial-mcc"} == $key) ? "selected" : "";
                    echo ">".$key." - ".$value."</option>";
                }

                echo '
                </select>
            </div>
            <div class="col themed-grid-col">
                <label for="commercial-ewallet">eWallet</label>
                <br>
                <select name="commercial-ewallet" class="form-control OnboardingUpdateFields">';
                foreach($yes_no_select as $value) {
                    echo '<option';
                    if($onboarding_data->data->{"commercial-ewallet"} == $value) { echo " selected"; }
                    echo '>'.$value.'</option>';
                }
                echo '
                </select>
            </div>
            <div class="col themed-grid-col">
                <label for="commercial-recurring">Recurring / Subscriptions</label>
                <br>
                <select name="commercial-recurring" class="form-control">';
                    foreach($yes_no_select as $value) {
                        echo '<option';
                        if($onboarding_data->data->{"commercial-recurring"} == $value) { echo " selected"; }
                        echo '>'.$value.'</option>';
                    }
                    echo '
                </select>
            </div>
        </div>
        <div class="row row-cols-md-1 mb-1">
            <div class="col themed-grid-col"><h2>Target Market</h2></div>
        </div>
        <div class="row row-cols-md-4 mb-4">
            <div class="col themed-grid-col">
                <label for="commercial-market-eu">EU/EEA</label>
                <br><input name="commercial-market-eu" class="commercial-market form-control" value="'; echo $onboarding_data->data->{"commercial-market-eu"}; echo '"></div>
            <div class="col themed-grid-col">
                <label for="commercial-market-uk">UK</label>
                <br><input name="commercial-market-uk" class="commercial-market form-control" value="'; echo $onboarding_data->data->{"commercial-market-uk"}; echo '"></div>
            <div class="col themed-grid-col">
                <label for="commercial-market-us">USA</label>
                <br><input name="commercial-market-us" class="commercial-market form-control" value="'; echo $onboarding_data->data->{"commercial-market-us"}; echo '"></div>
            <div class="col themed-grid-col">
                <label for="commercial-market-row">ROW</label>
                <br><input name="commercial-market-row" class="commercial-market form-control" value="'; echo $onboarding_data->data->{"commercial-market-row"}; echo '"></div>
        </div>
        <div class="row row-cols-md-1 mb-1 targetMarketExceeds" style="display:none;">
            <div class="col md-12 alert-warning rounded text-muted alert">
                <div>Target market does not equal 100%. This may be incorrect.</div>
            </div>
        </div>
    </div>
    <div class="tab-pane" name="personel" role="tabpanel" aria-labelledby="profile-tab">
        <h2>Key Personnel</h2>
        <div class="col md-12 alert-warning rounded text-muted alert">
            <div>Key personel is defined as:<br>
            <ul>
                <li>Any director that is registered at the business authorities</li>
                <li>Any individual who controls more than 25% of the company</li>
                <li>Any individual who indirectly can influence the company decisions</li>
            </ul>
        </div>
        </div>
        <table class="table table-striped table-sm" name="keyPersonnelTable">
            <thead>
                <tr>
                    <td>Name</td>
                    <td>Date of Birth</td>
                    <td>Passport</td>
                    <td>Frontpage of Driving License</td>
                    <td>Back of Driving License</td>
                    <td>Address Verification</td>
                </tr>
            </thead>
            <tbody>
            ';
                foreach($onboarding_data->key_personnel as $value) {
                    echo '<tr name="1s1ZtAvPtWXd">';
                    echo '<td>'.$value->name.'</td>';
                    echo '<td>'.$value->date_of_birth.'</td>';

                    echo "<td>";
                        if(!$value->files->passport)
                            echo "Not available";
                        else
                            echo "<a target='_NEW' href=\"data:image/jpg;base64,".$value->files->data->passport."\">Validate</a>";
                    echo "</td>";

                    echo "<td>";
                    if(!$value->files->driving_license_front)
                        echo "Not available";
                    else
                        echo "<a target='_NEW' href=\"data:image/jpg;base64,".$value->files->data->driving_license_front."\">Validate</a>";

                    echo "</td>";

                    echo "<td>";

                    if(!$value->files->driving_license_back)
                        echo "Not available";
                    else
                        echo "<a target='_NEW' href=\"data:image/jpg;base64,".$value->files->data->driving_license_back."\">Validate</a>";

                    echo "</td>";

                    echo "<td>";

                    if(!$value->files->address)
                        echo "Not available";
                    else
                        echo "<a target='_NEW' href=\"data:image/jpg;base64,".$value->files->data->address."\">Validate</a>";

                    echo "</td>";


                    echo '</tr>';
                }
            echo '
        </table>
    </div>
    <div class="tab-pane" name="bank" role="tabpanel" aria-labelledby="messages-tab">
        <h2>Banking Data</h2>
        <div class="row row-cols-md-2 mb-2">
            <div class="col themed-grid-col">
                <label for="bank-account-location">Account location</label><br>
                <select name="bank-account-location" class="form-control">';
                foreach($partner->ListCountry() as $key=>$value) {
                    echo "<option value='".$value->id."' ";
                    echo (isset($onboarding_data->data->{"bank-account-location"}) && $onboarding_data->data->{"bank-account-location"} == $value->id) ? "selected" : "";
                    echo ">".$value->name."</option>";
                }
                echo '
            </select></div>
            <div class="col themed-grid-col">
                <label for="bank-account-currency">Account currency</label><br>
                <select name="bank-account-currency" class="form-control">';
                    foreach($currency->currency_list() as $value) {
                        echo "<option value='".$value."' ";
                        echo (isset($onboarding_data->data->{"bank-account-currency"}) && $onboarding_data->data->{"bank-account-currency"} == $currency->currency($value)[0]) ? "selected" : "";
                        echo ">".$currency->currency($value)[0]."</option>";
                    }
                    echo '
                </select></div>
        </div>
        <div class="row row-cols-md-1 mb-1">
            <div class="col themed-grid-col">
                <label for="bank-name">Bank Name</label><br>
                <input name="bank-name" class="form-control" value="'.$onboarding_data->data->{"bank-name"}.'">
            </div>
        </div>
        <div class="row row-cols-md-1 mb-1">
            <div class="col themed-grid-col">
                <label for="bank-settlement-requirements">Settlement requirements</label><br>
                <input name="bank-settlement-requirements" class="form-control" value="'.$onboarding_data->data->{"bank-settlement-requirements"}.'">
            </div>
        </div>
        <div class="row row-cols-md-2 mb-2">
            <div class="col themed-grid-col">
                <label for="bank-iban">IBAN Number</label><br>
                <input name="bank-iban" class="form-control" value="'.$onboarding_data->data->{"bank-iban"}.'">
            </div>
            <div class="col themed-grid-col">
                <label for="bank-bic">BIC Number</label><br>
                <input name="bank-bic" class="form-control" value="'.$onboarding_data->data->{"bank-bic"}.'">
            </div>
        </div>
    </div>
    <div class="tab-pane" name="digital-signature" role="tabpanel" aria-labelledby="settings-tab"><br>
        <h2>Finalize</h2>
        <input type="submit" class="w-100 btn btn-lg btn-primary" name="ApproveApplication" value="Approve Application">
        <input type="submit" class="w-100 btn btn-lg btn-primary" name="DeclineApplication" value="Decline Application">
    </div>
</div>
</form>
';
echo foot();