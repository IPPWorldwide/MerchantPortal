<?php
include("../base.php");
if(isset($REQ["id"])) {
    $merchant_data = $ipp->MerchantDataUpdate($REQ);
}
if(isset($REQ["support_status"])) {
    $ipp->MerchantSupportUpdate($REQ["support_status"]);
    die();
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
$actions->get_action("merchant_data");
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
                    <div class="box-body">
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
                    <button type="button" class="btn btn-primary mb-3 btnSupportSettings">'.$lang["COMPANY"]["DATA"]["SHOW_SUPPORT"].'</button> 
                </div>
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
    <div class="modal fade" id="settingsSupport" tabindex="-1" role="dialog" aria-labelledby="settingsAcquirerModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Support</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeModal">'.$lang["COMPANY"]["DATA"]["DISABLE_SUPPORT"].'</button>
                    <button type="button" class="btn btn-primary confirm">'.$lang["COMPANY"]["DATA"]["ENABLE_SUPPORT"].'</button>
                </div>
            </div>
        </div>
    </div>    
';
echo foot();
