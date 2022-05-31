<?php
include("../base.php");
if(isset($REQ["id"])) {
    $merchant_data = $ipp->MerchantDataUpdate($REQ);
}
if(isset($REQ["acquirer_id"]) && isset($REQ["acquirer_data"])) {
    $acquirer_data = array();
    parse_str($REQ["acquirer_data"], $acquirer_data);
    $merchant_data = $ipp->MerchantAcquirerUpdate($REQ["acquirer_id"],$acquirer_data);
    echo json_encode($acquirer_data);
    die();
}
$merchant_data = $ipp->MerchantData();
echo head();
echo '
        <form action="?" method="POST" class="form">
            <h2>'.$lang["COMPANY"]["DATA"]["HEADER"].'</h2>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">'.$lang["COMPANY"]["DATA"]["MERCHANT_ID"].'<br /><input name="id" class="form-control" value="'.$merchant_data->id.'" readonly></div>
                <div class="col themed-grid-col">'.$lang["COMPANY"]["DATA"]["API_PASSWORD"].'<br /><input name="security[key1]" class="form-control" value="'.$merchant_data->security->key1.'"></div>
                <div class="col themed-grid-col">'.$lang["COMPANY"]["DATA"]["PAYMENT_PASSWORD"].'<br /><input name="security[key2]" class="form-control" value="'.$merchant_data->security->key2.'"></div>
            </div>
            <h2>'.$lang["COMPANY"]["DATA"]["HEADER_SESSION"].'</h2>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">'.$lang["COMPANY"]["DATA"]["USER_ID"].'<br /><input name="id" class="form-control" value="'.$ipp->getSession()["user_id"].'" readonly></div>
                <div class="col themed-grid-col">'.$lang["COMPANY"]["DATA"]["SESSION_KEY"].'<br /><input name="security[key1]" class="form-control" value="'.$ipp->getSession()["session_id"].'"></div>
            </div>
            <h2>'.$lang["COMPANY"]["DATA"]["HEADER_COMPANY"].'</h2>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">'.$lang["COMPANY"]["DATA"]["COMPANY_NAME"].'<br /><input name="meta[company][name]" class="form-control" value="'; echo isset($merchant_data->meta_data->company->name) ? $merchant_data->meta_data->company->name : ""; echo '"></div>
                <div class="col themed-grid-col">'.$lang["COMPANY"]["DATA"]["REGNO"].'<br /><input name="meta[company][reg_id]" class="form-control" value="'; echo isset($merchant_data->meta_data->company->reg_id) ? $merchant_data->meta_data->company->reg_id : ""; echo '"></div>
                <div class="col themed-grid-col">'.$lang["COMPANY"]["DATA"]["EUVAT"].'<br /><input name="meta[company][vat]" class="form-control" value="'; echo isset($merchant_data->meta_data->company->vat) ? $merchant_data->meta_data->company->vat : ""; echo '"></div>
            </div>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">'.$lang["COMPANY"]["DATA"]["ADDRESS"].'<br /><input name="meta[address][address]" class="form-control" value="'; echo isset($merchant_data->meta_data->address->address) ? $merchant_data->meta_data->address->address : ""; echo '"></div>
                <div class="col themed-grid-col">'.$lang["COMPANY"]["DATA"]["POSTAL"].'<br /><input name="meta[address][postal]" class="form-control" value="'; echo isset($merchant_data->meta_data->address->postal) ? $merchant_data->meta_data->address->postal : ""; echo '"></div>
                <div class="col themed-grid-col">'.$lang["COMPANY"]["DATA"]["CITY"].'<br /><input name="meta[address][city]" class="form-control" value="'; echo isset($merchant_data->meta_data->address->city) ? $merchant_data->meta_data->address->city : ""; echo '"></div>
            </div>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">'.$lang["COMPANY"]["DATA"]["COUNTRY"].'<br /><input name="meta[address][country]" class="form-control" value="'; echo isset($merchant_data->meta_data->address->country) ? $merchant_data->meta_data->address->country : ""; echo '"></div>
                <div class="col themed-grid-col">'.$lang["COMPANY"]["DATA"]["PHONE"].'<br /><input name="meta[company][phone]" class="form-control" value="'; echo isset($merchant_data->meta_data->company->phone) ? $merchant_data->meta_data->company->phone : ""; echo '"></div>
                <div class="col themed-grid-col">'.$lang["COMPANY"]["DATA"]["CARDHOLDER_DESCRIPTION"].'<br /><input name="meta[processing][descriptor]" class="form-control" value="'; echo isset($merchant_data->meta_data->processing->descriptor) ? $merchant_data->meta_data->processing->descriptor : ""; echo '"></div>
            </div>
            <div class="row row-cols-md-2 mb-2">
                <div class="col themed-grid-col">
                    <h2>'.$lang["COMPANY"]["DATA"]["ACQUIRERS"].'</h2>
                    <table class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                        <thead>
                        <tr>
                            <th>'.$lang["COMPANY"]["DATA"]["NAME"].'</th>
                            <th>'.$lang["COMPANY"]["DATA"]["ID"].'</th>
                            <th>'.$lang["COMPANY"]["DATA"]["WEBSITE"].'</th>
                        </tr>
                        </thead>
                        <tbody>
                        ';
                        foreach($merchant_data->acquirers as $key=>$value) {
                            echo "<tr><td>".$value->name."</td><td>".$value->id."</td><td></td><td><a href='".$value->url."' target='_BLANK'>".$value->url."</a></td></tr>";
                        }
                        echo '
                        </tbody>
                    </table>
                </div>
                <div class="col themed-grid-col">
                    <div class="box-header">
                        <h2>'.$lang["COMPANY"]["DATA"]["ACQUIRER_RULES"].'</h2>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="radio">
                                    <label class="ui-check ui-check-lg">
                                        <input type="radio" name="rule_type" value="AABB-ABCD-ABCD"';
                                        if($merchant_data->rules->id == "AABB-ABCD-ABCD") { echo "checked"; } echo '>
                                        <i class="dark-white"></i>
                                        '.$lang["COMPANY"]["DATA"]["ROUND_ROBIN"].'
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="radio">
                                    <label class="ui-check ui-check-lg">
                                        <input type="radio" name="rule_type" value="BBBB-CCCC-DDDD" ';
                                        if($merchant_data->rules->id == "BBBB-CCCC-DDDD") { echo "checked"; } echo '>
                                        <i class="dark-white"></i>
                                        '.$lang["COMPANY"]["DATA"]["ROUND_ROBIN_WITH_MAX"].'
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="radio">
                                    <label class="ui-check ui-check-lg">
                                        <input type="radio" name="rule_type" value="CCCC-DDDD-EEEE" '; if($merchant_data->rules->id == "CCCC-DDDD-EEEE") { echo "checked"; } echo '>
                                        <i class="dark-white"></i>
                                        '.$lang["COMPANY"]["DATA"]["ACQUIRER_WITH_LOWEST_VOLUME"].'
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="radio">
                                    <label class="ui-check ui-check-lg">
                                        <input type="radio" name="rule_type" value="DDDD-EEEE-FFFF" '; if($merchant_data->rules->id == "DDDD-EEEE-FFFF") { echo "checked"; } echo '>
                                        <i class="dark-white"></i>
                                        '.$lang["COMPANY"]["DATA"]["ACQUIRER_WITH_LOWEST_VOLUME_MAX"].'
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="box-header">
                            <h2>'.$lang["COMPANY"]["DATA"]["RULE_SETTINGS"].'</h2>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                                        <thead>
                                        <tr>
                                            <th>'.$lang["COMPANY"]["DATA"]["NAME"].'</th>
                                            <th>'.$lang["COMPANY"]["DATA"]["DAILY_MAX"].'</th>
                                            <th>'.$lang["COMPANY"]["DATA"]["SUPPORTED_BRANDS"].'</th>
                                            <th>'.$lang["COMPANY"]["DATA"]["DATA"].'</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        ';
                                        foreach($merchant_data->acquirers as $value) {
                                            $settings = ((bool)$value->settings == NULL) ? "{}" : $value->settings;
                                            echo "<tr>";
                                            echo "<td>";
                                            echo $value->name;
                                            echo "</td>";
                                            if ($merchant_data->rules->id == "BBBB-CCCC-DDDD" || $merchant_data->rules->id == "DDDD-EEEE-FFFF") {
                                                echo "<td>";
                                                echo "<input name=\"rules[".$value->id."][daily_limit]\" type=\"text\" class=\"form-control\"";
                                                if(isset($merchant_data->rules->acquirer_rules->{$value->id}->max_limit)) {
                                                    echo " value=\"".$merchant_data->rules->acquirer_rules->{$value->id}->max_limit."\"";
                                                } else {
                                                    echo "";
                                                }
                                                echo ">";
                                                echo "</td>";
                                            } else {
                                                echo "<td>";
                                                echo "</td>";
                                                echo "<td>";
                                                echo "</td>";
                                            }
                                                echo "<td>";
                                                    echo "<button class='btn btn-info btnAcquirerSettings' data-id='".$value->id."' data-title='".$value->name."' data-fields='".json_encode($value->fields)."' data-field-values='".($settings)."'>".$lang["COMPANY"]["DATA"]["SETTINGS"]."</button>";
                                                echo "</td>";
                                            echo "</tr>";
                                        }
                                        echo '
                                        </tbody></table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row-cols-md-1 mb-1">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mb-3">'.$lang["COMPANY"]["DATA"]["SAVE"].'</button>
                </div>

            </div>
        </form>
    <div class="modal fade" id="settingsAcquirerModal" tabindex="-1" role="dialog" aria-labelledby="settingsAcquirerModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" name="acquirer_id" id="acquirer-id" readonly>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeModal">'.$lang["COMPANY"]["DATA"]["CLOSE"].'</button>
                    <button type="button" class="btn btn-primary confirm">'.$lang["COMPANY"]["DATA"]["SAVE"].'</button>
                </div>
            </div>
        </div>
    </div>
';
echo foot();