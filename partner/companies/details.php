<?php
include("../b.php");

if(isset($REQ["id"]) && isset($REQ["meta"])) {
    $partner->MerchantDataUpdate($REQ);
}

$partner_acquirers = $partner->ListAcquirers();
$merchant_data = $partner->MerchantData($REQ["id"]);
$merchant_acquirers = [];

foreach($merchant_data->acquirers as $key=>$value) {
    $merchant_acquirers[$value->id] = $value->name;
}

$invoices = $partner->ListCompanyInvoices($REQ["id"]);
$pos_devices = $partner->ListPOSDevices($REQ["id"]);
$subscription_plans = $partner->ListSubscriptionPlans();

$mcc_list = $mcc->list();

echo head();
?>
            <h2>Company Data</h2>
    <form action="?" method="POST" class="form">
        <div class="row row-cols-md-3 mb-3">
            <div class="col themed-grid-col">Merchant ID:<br /><input name="id" class="form-control" value="<?php echo $merchant_data->id; ?>" readonly></div>
            <div class="col themed-grid-col">Payment Password:<br /><input name="security[key2]" class="form-control" value="<?php echo $merchant_data->security->key2; ?>"></div>
            <div class="col themed-grid-col">Subscription Plan:<br />
                <select name="subscription_plan" class="form-control" >
                    <option value="0">Choose plan</option>
                    <?php
                    foreach($subscription_plans as $value) {
                        echo "<option value='".$value->id."'";

                        if(isset($merchant_data->invoices->plan->data->id) && $value->id == $merchant_data->invoices->plan->data->id)
                            echo " selected";

                        echo ">".$value->name."</option>";
                    }
                    ?>
                </select></div>
            <div class="col themed-grid-col">MCC:<br />
                <select name="mcc" class="form-control" >
                    <option value="0">Choose MCC</option>
                    <?php
                    foreach($mcc_list as $key=>$value) {
                        echo "<option value='".$key."'";

                        if(isset($merchant_data->mcc->code) && $key == $merchant_data->mcc->code)
                            echo " selected";

                        echo ">".$key." - ".$value."</option>";
                    }
                    ?>
                </select></div>
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
            <div class="col themed-grid-col">Doing business as (Cardholder Description):<br /><input name="meta[processing][descriptor]" class="form-control" value="<?php echo isset($merchant_data->meta_data->processing->descriptor) ? $merchant_data->meta_data->processing->descriptor : ""; ?>"></div>
        </div>
        <div class="row row-cols-md-2 mb-2">
            <div class="col themed-grid-col">
                <h2>Acquirers</h2>
                <table class="table table-striped table-sm">
                    <thead>
                    <tr>
                        <th>Active</th>
                        <th>Name</th>
                        <th>Acquirer ID</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($partner_acquirers as $key=>$value) {
                        echo "<tr><td><input name='acquirers[$key]' type='checkbox'";
                        if(isset($merchant_acquirers[$key])) {
                            echo " checked ";
                        }
                        echo ">";
                        echo "</td><td>".$value->name."</td><td>".$value->id."</td><td></td></tr>";
                    }
                    ?>
                    </tbody>
                </table>

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
                                        else {
                                            echo "<td>";
                                                echo "Non applicable";
                                            echo "</td>";
                                            echo "<td>";
                                                echo "Non applicable";
                                            echo "</td>";
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
            <div class="col themed-grid-col">
                <h2>Terminal Devices</h2>
                <table class="table table-striped table-sm" id="terminal_devices">
                    <thead>
                    <tr>
                        <th scope="col">Device ID</th>
                        <th scope="col">Gateway MID</th>
                        <th scope="col">Type</th>
                        <th scope="col">Registered name</th>
                        <th>#</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($pos_devices as $value) {
                        echo "<tr>";
                        echo "
                  <td>".$value->id."</td>
                  <td>".$value->gateway_mid."</td>
                  <td>".$value->type."</td>
                  <td>".$value->name."</td>
                  <td><input type='checkbox' name='pos_device[".$value->id."]' ";
                        if($value->company_id == $REQ["id"]) {
                            echo "checked";
                        }
                        echo ">
                  </td>
                </tr>";
                    }
                    ?>
                    </tbody>
                </table>
                <h2>Invoices</h2>
                 <table class="table table-striped table-sm">
                        <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Package</th>
                            <th scope="col">Amount</th>
                            <th scope="col">VAT</th>
                            <th scope="col">Period End</th>
                            <th scope="col">Paid</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($invoices as $value) {
                            echo "<tr>";
                            echo "
              <td>".$value->id."</td>
              <td>".$value->package_id."</td>
              <td>".$value->amount_readable." ".$value->currency_txt."</td>
              <td>".$value->vat."%</td>
              <td>".date("Y-m-d", $value->period_end)."</td>
              <td>";
                            if($value->cancelled == 1)
                                echo "Cancelled";
                            elseif($value->paid == 1)
                                echo "Paid";
                            else
                                echo "Unpaid";
                            echo "</td>
            </tr>";
                        }
                        ?>
                        </tbody>
                    </table>
            </div>
        </div>

        <div class="row row-cols-md-1 mb-1">

        </div>
        <div class="row">
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary mb-3">Save</button>
            </div>
        </div>
    </form>

<?php

$load_script[] = "https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js";
$load_css[] = "https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css";
$inline_script[] = "$( document ).ready(function() {
var table = $('#terminal_devices').DataTable({        
    lengthMenu: [
            [3, 5, 10, -1],
            [3, 5, 10, 'All']
        ]
        });
        
        
        $('.form').on('submit', function(e){
      var form = this;

      // Encode a set of form elements from all pages as an array of names and values
      var params = table.$('input,select,textarea').serializeArray();

      // Iterate over all form elements
      $.each(params, function(){
         // If element doesn't exist in DOM
         if(!$.contains(document, form[this.name])){
            // Create a hidden element
            $(form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', this.name)
                  .val(this.value)
            );
         }
      });
   });

});
";
echo foot();