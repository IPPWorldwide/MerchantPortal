<?php
include("../b.php");

if(isset($REQ["submit"])) {
    include(BASEDIR . "controller/IPPConfig.php");
    $config = new IPPConfig();
    if(!isset($REQ["IPPCONFIG"]['PORTAL_DEACTIVATE_SEARCH'])){
        $REQ["IPPCONFIG"]['PORTAL_DEACTIVATE_SEARCH'] = 0;    
    }
    if(!isset($REQ["IPPCONFIG"]['PORTAL_DEACTIVATE_VIRTUAL_TERMINAL'])){
        $REQ["IPPCONFIG"]['PORTAL_DEACTIVATE_VIRTUAL_TERMINAL'] = 0;    
    }
    if(!isset($REQ["IPPCONFIG"]['PORTAL_LOCAL_DEACTIVATE_REFUNDS'])){
        $REQ["IPPCONFIG"]['PORTAL_LOCAL_DEACTIVATE_REFUNDS'] = 0;    
    }
    if(!isset($REQ["IPPCONFIG"]['PORTAL_LOCAL_DEACTIVATE_VOID'])){
        $REQ["IPPCONFIG"]['PORTAL_LOCAL_DEACTIVATE_VOID'] = 0;    
    }
    if(!isset($REQ["IPPCONFIG"]['PORTAL_LOCAL_HIDE_TOTAL_VOLUME'])){
        $REQ["IPPCONFIG"]['PORTAL_LOCAL_HIDE_TOTAL_VOLUME'] = 0;    
    }
    foreach($REQ["IPPCONFIG"] as $key=>$value) {
        $config->UpdateConfig($key,$value);
    }
    $config->UpdateConfig("partner_company_id",$REQ["partner_merchant_id"]);
    $config->UpdateConfig("partner_company_key2", $REQ["partner_merchant_key2"]);
    $config = $config->WriteConfig();
    unset($REQ["IPPCONFIG"]);
    $partner->UpdateData($REQ);
}
$partner_data = $partner->PartnerData();
echo head();
echo '
        <form action="?" method="POST" class="form">
            <h2>'.$lang["PARTNER"]["DATA"]["HEADER"].'</h2>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">'.$lang["PARTNER"]["DATA"]["PARTNER_ID"].'<br /><input name="id" class="form-control" value="'.$partner_data->id.'" readonly></div>
                <div class="col themed-grid-col">'.$lang["PARTNER"]["DATA"]["KEY_1"].'<br /><input name="security[key1]" class="form-control" value="'.$partner_data->security->key1.'"></div>
                <div class="col themed-grid-col">'.$lang["PARTNER"]["DATA"]["KEY_2"].'<br /><input name="security[key2]" class="form-control" value="'.$partner_data->security->key2.'"></div>
            </div>
            <h2>'.$lang["PARTNER"]["DATA"]["DETAILS"].'</h2>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">'.$lang["PARTNER"]["DATA"]["NAME"].'<br /><input name="meta[name]" class="form-control" value="'; echo $partner_data->meta_data->name ?? ""; echo '"></div>
            </div>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">'.$lang["PARTNER"]["DATA"]["COUNTRY"].'<br />
                    <select name="meta[country]" class="form-control" >
                        ';
                        foreach($partner->ListCountry() as $key=>$value) {
                            echo "<option value='".$value->id."' ";
                            echo (isset($partner_data->country->id) && $partner_data->country->id == $value->id) ? "selected" : "";
                            echo ">".$value->name."</option>";
                        }
                        echo '
                    </select>
                    </div>
            </div>
            <h2>'.$lang["PARTNER"]["DATA"]["PARTNER_INVOICES"].'</h2>
            <div class="row row-cols-md-12 mb-12">
                <div class="col themed-grid-col">'.$lang["PARTNER"]["DATA"]["PAYMENT_SLIP"].'<br /><input name="meta[invoicetext]" class="form-control" value="'; echo $partner_data->meta_data->meta->invoicetext ?? ""; echo '"></div>
            </div>
            <div class="row row-cols-md-6 mb-6">
                <div class="col themed-grid-col">'.$lang["PARTNER"]["DATA"]["MERCHANT_ID"].'<br /><input name="partner_merchant_id" class="form-control" value="'; echo $partner_data->merchant_id ?? ""; echo '"></div>
                <div class="col themed-grid-col">'.$lang["PARTNER"]["DATA"]["MERCHANT_KEY2"].'<br /><input name="partner_merchant_key2" class="form-control" value="'; echo $partner_data->merchant_key ?? ""; echo '"></div>
            </div>
            <div class="row row-cols-md-2 mb-2">
                <div class="col themed-grid-col">
                    <h2>'.$lang["PARTNER"]["DATA"]["ACQUIRERS"].'</h2>
                    <table class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                        <thead>
                        <tr>
                            <th>'.$lang["PARTNER"]["DATA"]["ACQUIRERS_NAME"].'</th>
                            <th>'.$lang["PARTNER"]["DATA"]["ACQUIRERS_ID"].'</th>
                            <th>'.$lang["PARTNER"]["DATA"]["ACQUIRERS_WEBSITE"].'</th>
                        </tr>
                        </thead>
                        <tbody>
                        ';
                        foreach($partner_data->acquirers as $key=>$value) {
                            echo "<tr><td>".$value->name."</td><td>".$value->id."</td><td></td><td><a href='".$value->url."' target='_BLANK'>".$value->url."</a></td></tr>";
                        }
                        echo '
                        </tbody>
                    </table>
                </div>
                <div class="col themed-grid-col">
                    <h2>'.$lang["PARTNER"]["DATA"]["PORTAL_SETTINGS"].'</h2>
                    <table class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                        <thead>
                        <tr>
                            <th>'.$lang["PARTNER"]["DATA"]["SETTING_NAME"].'</th>
                            <th>'.$lang["PARTNER"]["DATA"]["SETTING_VALUE"].'</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>'.$lang["PARTNER"]["DATA"]["LOCAL_PORTAL_TITLE"].'</td>
                                <td><input type="input" class="form form-control" name="IPPCONFIG[PORTAL_TITLE]" value="'.$IPP_CONFIG["PORTAL_TITLE"].'"></td>
                            </tr>
                            <tr>
                                <td>'.$lang["PARTNER"]["DATA"]["LOCAL_DEACTIVATE_SEARCH"].'</td>
                                <td>
                                <label class="switch">
                                <input type="checkbox" class="form form-control"  name="IPPCONFIG[PORTAL_DEACTIVATE_SEARCH]" value="1"';if(isset($IPP_CONFIG["PORTAL_DEACTIVATE_SEARCH"]) && $IPP_CONFIG["PORTAL_DEACTIVATE_SEARCH"] === "1"){ echo 'checked'; }; echo '>
                                <span class="slider round" ></span>
                                </label>
                                </td>
                            </tr>
                            <tr>
                                <td>'.$lang["PARTNER"]["DATA"]["LOCAL_DEACTIVATE_VIRTUAL_TERMINAL"].'</td>
                                <td>
                                <label class="switch">
                                <input type="checkbox" class="form form-control"  name="IPPCONFIG[PORTAL_DEACTIVATE_VIRTUAL_TERMINAL]" value="1"';if(isset($IPP_CONFIG["PORTAL_DEACTIVATE_VIRTUAL_TERMINAL"]) && $IPP_CONFIG["PORTAL_DEACTIVATE_VIRTUAL_TERMINAL"] === "1"){ echo 'checked'; }; echo '>
                                <span class="slider round" ></span>
                                </label>
                                </td>
                            </tr>
                            <tr>
                                <td>'.$lang["PARTNER"]["DATA"]["LOCAL_DEACTIVATE_REFUNDS"].'</td>
                                <td>
                                <label class="switch">
                                <input type="checkbox" class="form form-control"  name="IPPCONFIG[PORTAL_LOCAL_DEACTIVATE_REFUNDS]" value="1"';if(isset($IPP_CONFIG["PORTAL_LOCAL_DEACTIVATE_REFUNDS"]) && $IPP_CONFIG["PORTAL_LOCAL_DEACTIVATE_REFUNDS"] === "1"){ echo 'checked'; }; echo '>
                                <span class="slider round" ></span>
                                </label>
                                </td>
                            </tr>
                            <tr>
                                <td>'.$lang["PARTNER"]["DATA"]["LOCAL_DEACTIVATE_VOIDS"].'</td>
                                <td>
                                <label class="switch">
                                <input type="checkbox" class="form form-control"  name="IPPCONFIG[PORTAL_LOCAL_DEACTIVATE_VOID]" value="1"';if(isset($IPP_CONFIG["PORTAL_LOCAL_DEACTIVATE_VOID"]) && $IPP_CONFIG["PORTAL_LOCAL_DEACTIVATE_VOID"] === "1"){ echo 'checked'; }; echo '>
                                <span class="slider round" ></span>
                                </label>
                                </td>
                            </tr>
                            <tr>
                                <td>'.$lang["PARTNER"]["DATA"]["LOCAL_HIDE_TOTAL_VOLUME"].'</td>
                                <td>
                                <label class="switch">
                                <input type="checkbox" class="form form-control"  name="IPPCONFIG[PORTAL_LOCAL_HIDE_TOTAL_VOLUME]" value="1"';if(isset($IPP_CONFIG["PORTAL_LOCAL_HIDE_TOTAL_VOLUME"]) && $IPP_CONFIG["PORTAL_LOCAL_HIDE_TOTAL_VOLUME"] === "1"){ echo 'checked'; }; echo '>
                                <span class="slider round" ></span>
                                </label>
                                </td>
                            </tr> 
                        </tbody>
                    </table>
                </div>
            </div>            
            <div class="row row-cols-md-1 mb-1">
                <div class="d-flex justify-content-end">
                    <button type="submit" name="submit" class="btn btn-primary mb-3">'.$lang["PARTNER"]["DATA"]["SAVE"].'</button>
                </div>
            </div>
        </form>
';
echo foot();