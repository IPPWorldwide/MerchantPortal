<?php
include("../../b.php");
$onboarding_data = $partner->OnboardingData($REQ["id"]);

if(isset($REQ["ApproveApplication"])) {
    $state = 0;
    if($REQ["ApproveApplication"] === "Approve" || $REQ["ApproveApplication"] === "ApproveCompany")
        $state = 1;

    $dataApproval = (object)$REQ;
    $dataApproval->personel = new StdClass;
    $dataApproval->personel = $onboarding_data->key_personnel;
    echo json_encode($partner->OnboardingPartnerData($REQ["company_id"],$dataApproval,$state));
    die();
}

$all_onboarding_files_available = true;
echo head();
echo '
<h1>'.$onboarding_data->{"name"}.'</h1>
';
if($onboarding_data->validated->partner && !$user_data->super_admin && !$user_data->compliance_admin) {
    echo "Application have already been approved. Contact your Compliance Administrator or Super Administrator for more information.";
    die();
}
echo '
<form method="POST" action="?" name="OnboardingForm" id="OnboardingForm">
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
                    if(isset($onboarding_data->data->{"commercial-ewallet"}))
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
                        if(isset($onboarding_data->data->{"commercial-recurring"}))
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
                <br><input name="commercial-market-eu" class="commercial-market form-control" value="'; echo $onboarding_data->data->{"commercial-market-eu"} ?? "0"; echo '"></div>
            <div class="col themed-grid-col">
                <label for="commercial-market-uk">UK</label>
                <br><input name="commercial-market-uk" class="commercial-market form-control" value="'; echo $onboarding_data->data->{"commercial-market-uk"} ?? "0"; echo '"></div>
            <div class="col themed-grid-col">
                <label for="commercial-market-us">USA</label>
                <br><input name="commercial-market-us" class="commercial-market form-control" value="'; echo $onboarding_data->data->{"commercial-market-us"} ?? "0"; echo '"></div>
            <div class="col themed-grid-col">
                <label for="commercial-market-row">ROW</label>
                <br><input name="commercial-market-row" class="commercial-market form-control" value="'; echo $onboarding_data->data->{"commercial-market-row"} ?? "0"; echo '"></div>
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
                    echo '<tr>';
                    echo "<td><input type='text' name='KEY_PERSONNEL[".$value->id."][name]' value='".$value->name."'></td>";
                    echo '<td>'.$value->date_of_birth.'</td>';

                    echo "<td>";
                        if(!$value->files->passport) {
                            echo "Not available";
                            if($all_onboarding_files_available)
                                $all_onboarding_files_available = false;
                        }
                        else {
                            echo "<a target='_NEW' href=\"data:image/jpg;base64,".$value->files->data->passport."\">Validate</a>";
                            echo "<table>";
                                echo "<tr>";
                                    echo "<td>";
                                        echo "Personal code number:";
                                echo "</td>";
                                    echo "<td>";
                                        echo "<input type='text' name='KEY_PERSONNEL[".$value->id."][passport_personal_code_number]' value='"; echo $onboarding_data->confirmed_version->KEY_PERSONNEL->{$value->id}->passport_personal_code_number ?? ""; echo "'>";
                                    echo "</td>";
                                echo "</tr>";
                                echo "<tr>";
                                    echo "<td>";
                                        echo "Nationality:";
                                    echo "</td>";
                                    echo "<td>";
                                        echo "<input type='text' name='KEY_PERSONNEL[".$value->id."][passport_nationality]' value='"; echo $onboarding_data->confirmed_version->KEY_PERSONNEL->{$value->id}->passport_nationality ?? ""; echo "'>";
                                    echo "</td>";
                                echo "</tr>";
                                echo "<tr>";
                                    echo "<td>";
                                        echo "Passport Number:";
                                    echo "</td>";
                                    echo "<td>";
                                        echo "<input type='text' name='KEY_PERSONNEL[".$value->id."][passport_no]' value='"; echo $onboarding_data->confirmed_version->KEY_PERSONNEL->{$value->id}->passport_no ?? ""; echo "'>";
                                    echo "</td>";
                                echo "</tr>";
                                echo "<tr>";
                                    echo "<td>";
                                    echo "Passport Expiry:";
                                    echo "</td>";
                                    echo "<td>";
                                    echo "<input type='date' name='KEY_PERSONNEL[".$value->id."][passport_expiry]' value='"; echo $onboarding_data->confirmed_version->KEY_PERSONNEL->{$value->id}->passport_expiry ?? ""; echo "'>";
                                    echo "</td>";
                                echo "</tr>";
                            echo "</table>";
                        }
                    echo "</td>";

                    echo "<td>";
                    if(!$value->files->driving_license_front) {
                        if($all_onboarding_files_available)
                            $all_onboarding_files_available = false;
                        echo "Not available";
                    }
                    else
                        echo "<a target='_NEW' href=\"data:image/jpg;base64,".$value->files->data->driving_license_front."\">Validate</a>";

                    echo "</td>";

                    echo "<td>";

                    if(!$value->files->driving_license_back) {
                        if($all_onboarding_files_available)
                            $all_onboarding_files_available = false;
                        echo "Not available";
                    }
                    else
                        echo "<a target='_NEW' href=\"data:image/jpg;base64,".$value->files->data->driving_license_back."\">Validate</a>";

                    echo "</td>";

                    echo "<td>";

                    if(!$value->files->address) {
                        if($all_onboarding_files_available)
                            $all_onboarding_files_available = false;
                        echo "Not available";
                    }
                    else {
                        echo "<a target='_NEW' href=\"data:image/jpg;base64,".$value->files->data->address."\">Validate</a>";
                        echo "<table>";
                            echo "<tr>";
                                echo "<td>";
                                    echo "Address:";
                                echo "</td>";
                                echo "<td>";
                                    echo "<input type='text' name='KEY_PERSONNEL[".$value->id."][address]' value='"; echo $onboarding_data->confirmed_version->KEY_PERSONNEL->{$value->id}->address ?? ""; echo "'>";
                                echo "</td>";
                            echo "</tr>";
                            echo "<tr>";
                                echo "<td>";
                                    echo "Postal:";
                                echo "</td>";
                                echo "<td>";
                                    echo "<input type='text' name='KEY_PERSONNEL[".$value->id."][postal]'value='"; echo $onboarding_data->confirmed_version->KEY_PERSONNEL->{$value->id}->postal ?? ""; echo "'>";
                                echo "</td>";
                            echo "</tr>";
                            echo "<tr>";
                                echo "<td>";
                                    echo "City:";
                                echo "</td>";
                                echo "<td>";
                                    echo "<input type='text' name='KEY_PERSONNEL[".$value->id."][city]'value='"; echo $onboarding_data->confirmed_version->KEY_PERSONNEL->{$value->id}->city ?? ""; echo "'>";
                                echo "</td>";
                            echo "</tr>";
                        echo "</table>";
                    }
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
    ';
    if(!$onboarding_data->validated->partner) {
        echo '
    <div class="tab-pane" name="digital-signature" role="tabpanel" aria-labelledby="settings-tab"><br>
        <h2>Finalize</h2>
        <div class="row row-cols-md-2 mb-2">
            <div class="col themed-grid-col">
                <button type="button" class="w-100 btn btn-lg btn-success ApproveApplication" name="ApproveApplication" ';
        if(!$all_onboarding_files_available)
            echo "disabled";
        echo '>Check Key Personnel & Approve Application</button><br /><br />
            </div>
            <div class="col themed-grid-col">
                <button type="button" class="w-100 btn btn-lg btn-warning DeclineApplication" name="DeclineApplication">Decline Application</button>
            </div>
        </div>
    </div>
    </div>';
    }
    echo '
</div>
</form>
<div class="modal fade" id="onboardingApplicationModal" tabindex="-1" role="dialog" aria-labelledby="onboardingApplicationModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="onboardingApplicationModalTitle">Handling Application</h5>
            </div>
            <div class="modal-body">
                <div class="dataLoading">One moment please.</div>
                <div class="IssueIdentified">An issue have been identified. Please confirm you wish to continue</div>                    
                <table class="table DeclinedPersons" style="display:none;">
                    <thead>
                        <tr>
                            <td>Name</td>    
                            <td>Reason</td>    
                        </tr>                    
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>    
                            <td></td>    
                        </tr>                    
                    </tbody>
                </table>
                <div class="NoIssuesFound">No issues was identified. The application have now been confirmed.<br />
                <a href="../" class="btn btn-success">Click here to see the Onbaording Overview</a></div>
                <div class="Declined">The application have now been declined.<br />
                <a href="../" class="btn btn-success">Click here to see the Onbaording Overview</a></div>
                
                <div id="OnboardingCompanyFinancials">
                    <div class="warning"></div>
                    <div class="Charts">
                        <div>
                            <canvas id="liquidity"></canvas>
                        </div>
                        <div>
                            <canvas id="returnAssets"></canvas>                    
                        </div>
                        <div>
                            <canvas id="returnEquity"></canvas>                    
                        </div>
                        <div>
                            <canvas id="solidity"></canvas>                    
                        </div>
                    </div>
                    <div class="ProfitData">
                        <table class="table">
                            <tr>
                                <td>Gross volume</td>        
                                <td class="GrossVolume"></td>
                            </tr>
                            <tr>
                                <td>Profit after Tax</td>        
                                <td class="ProfitAfterTax"></td>
                            </tr>
                            <tr>
                                <td>Free cash (After shortterm debt)</td>        
                                <td class="FreeCashAfterDebt"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer withErrors" style="display:none;">
                <button type="button" class="btn btn-success closeModal">'.$lang["PARTNER"]["ONBOARDING"]["CLOSE"].'</button>
                <button type="button" class="btn btn-danger confirmOnboardingAllDataSeen">'.$lang["PARTNER"]["ONBOARDING"]["CONTINUE_ANYWAY"].'</button>
            </div>
            <div class="modal-footer modal-footer-all-good" style="display:none;">
                <button type="button" class="btn btn-danger closeModal">'.$lang["PARTNER"]["ONBOARDING"]["CLOSE"].'</button>
                <button type="button" class="btn btn-success confirmOnboardingAllDataSeen">'.$lang["PARTNER"]["ONBOARDING"]["CONTINUE"].'</button>
            </div>
        </div>
    </div>
</div>
';
echo foot();