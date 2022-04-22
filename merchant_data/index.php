<?php
include("../base.php");
if(isset($REQ["id"])) {
    $merchant_data = $ipp->MerchantDataUpdate($REQ);
}
$merchant_data = $ipp->MerchantData();
echo head();
?>
        <form action="?" method="POST" class="form">
            <h2>Merchant Data</h2>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">Merchant ID:<br /><input name="id" class="form-control" value="<?php echo $merchant_data->id; ?>" readonly></div>
                <div class="col themed-grid-col">API Password:<br /><input name="security[key1]" class="form-control" value="<?php echo $merchant_data->security->key1; ?>"></div>
                <div class="col themed-grid-col">Payment Password:<br /><input name="security[key2]" class="form-control" value="<?php echo $merchant_data->security->key2; ?>"></div>
            </div>
            <h2>Session Data</h2>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">User ID :<br /><input name="id" class="form-control" value="<?php echo $ipp->getSession()["user_id"]; ?>" readonly></div>
                <div class="col themed-grid-col">Session Key:<br /><input name="security[key1]" class="form-control" value="<?php echo $ipp->getSession()["session_id"]; ?>"></div>
            </div>
            <h2>Company Details</h2>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">Company Name:<br /><input name="meta[company][name]" class="form-control" value="<?php echo isset($merchant_data->meta_data->company->name) ? $merchant_data->meta_data->company->name : ""; ?>"></div>
                <div class="col themed-grid-col">Company Registration No:<br /><input name="meta[company][reg_id]" class="form-control" value="<?php echo isset($merchant_data->meta_data->company->reg_id) ? $merchant_data->meta_data->company->reg_id : ""; ?>"></div>
                <div class="col themed-grid-col">EU VAT Number:<br /><input name="meta[company][vat]" class="form-control" value="<?php echo isset($merchant_data->meta_data->company->vat) ? $merchant_data->meta_data->company->vat : ""; ?>"></div>
            </div>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">Street Address:<br /><input name="meta[address][address]" class="form-control" value="<?php echo isset($merchant_data->meta_data->address->address) ? $merchant_data->meta_data->address->address : ""; ?>"></div>
                <div class="col themed-grid-col">Postal:<br /><input name="meta[address][postal]" class="form-control" value="<?php echo isset($merchant_data->meta_data->address->postal) ? $merchant_data->meta_data->address->postal : ""; ?>"></div>
                <div class="col themed-grid-col">City:<br /><input name="meta[address][city]" class="form-control" value="<?php echo isset($merchant_data->meta_data->address->city) ? $merchant_data->meta_data->address->city : ""; ?>"></div>
            </div>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">Country:<br /><input name="meta[address][country]" class="form-control" value="<?php echo isset($merchant_data->meta_data->address->country) ? $merchant_data->meta_data->address->country : ""; ?>"></div>
                <div class="col themed-grid-col">Phone number:<br /><input name="meta[company][phone]" class="form-control" value="<?php echo isset($merchant_data->meta_data->company->phone) ? $merchant_data->meta_data->company->phone : ""; ?>"></div>
                <div class="col themed-grid-col">Cardholder Description:<br /><input name="meta[processing][descriptor]" class="form-control" value="<?php echo isset($merchant_data->meta_data->processing->descriptor) ? $merchant_data->meta_data->processing->descriptor : ""; ?>"></div>
            </div>
            <div class="row row-cols-md-2 mb-2">
                <div class="col themed-grid-col">
                    <h2>Acquirers</h2>
                    <table class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>ID</th>
                            <th>Website</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($merchant_data->acquirers as $key=>$value) {
                            echo "<tr><td>".$value->name."</td><td>".$value->id."</td><td></td><td><a href='".$value->url."' target='_BLANK'>".$value->url."</a></td></tr>";
                        } ?>
                        </tbody>
                    </table>
                </div>
                <div class="col themed-grid-col">
                    <div class="box-header">
                        <h2>Acquirers Rules</h2>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="radio">
                                    <label class="ui-check ui-check-lg">
                                        <input type="radio" name="rule_type" value="AABB-ABCD-ABCD" <?php if($merchant_data->rules->id == "AABB-ABCD-ABCD") { echo "checked"; } ?>>
                                        <i class="dark-white"></i>
                                        Round Robin
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="radio">
                                    <label class="ui-check ui-check-lg">
                                        <input type="radio" name="rule_type" value="BBBB-CCCC-DDDD" <?php if($merchant_data->rules->id == "BBBB-CCCC-DDDD") { echo "checked"; } ?>>
                                        <i class="dark-white"></i>
                                        Round Robin with max limit
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="radio">
                                    <label class="ui-check ui-check-lg">
                                        <input type="radio" name="rule_type" value="CCCC-DDDD-EEEE" <?php if($merchant_data->rules->id == "CCCC-DDDD-EEEE") { echo "checked"; } ?>>
                                        <i class="dark-white"></i>
                                        Acquirer with lowest processing volume today
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="radio">
                                    <label class="ui-check ui-check-lg">
                                        <input type="radio" name="rule_type" value="DDDD-EEEE-FFFF" <?php if($merchant_data->rules->id == "DDDD-EEEE-FFFF") { echo "checked"; } ?>>
                                        <i class="dark-white"></i>
                                        Acquirer with lowest processing volume, with max limit
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="box-header">
                            <h2>Rule settings</h2>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Daily max volume</th>
                                            <th>Supported brands</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach($merchant_data->acquirers as $value) {
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
                                            }
                                            if ($merchant_data->rules->id == "CCCC-DDDD-EEEE") {

                                            }
                                            echo "</tr>";
                                        }
                                        ?>
                                        </tbody></table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row-cols-md-1 mb-1">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mb-3">Save</button>
                </div>

            </div>
        </form>
<?php
echo foot();