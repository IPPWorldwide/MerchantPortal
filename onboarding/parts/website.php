<?php
$mcc_list = $mcc->list();
$basket_limits = [
    400,
    600,
    800,
    1000,
    1200,
    1400,
    2000,
    5000,
    10000,
    15000,
    20000,
    25000,
    35000,
    50000,
    75000,
    100000
];
$onb_mcc = $onboarding_data->{"commercial-mcc"} ?? 5999;
$onb_basket_limits = $onboarding_data->{"basket_limit"} ?? 400;
$onb_delivery_timeframe = $onboarding_data->{"delivery_timeframe"} ?? 7;
?>
<div id='website'>
    <div class="step1 row website_check">
        <h2>URL</h2>
        <div class="col-9">

        </div>
        <div class="col-3">

        </div>
        <div class="col-3">
            <button class="form-control btn btn-success col-3" data-href="mcc">Confirm</button>
        </div>
    </div>
    <div class="step2 row mcc">
        <h2>MCC</h2>
        <select name="mcc" id="mcc" class="form-control" >
            <option value="0">Choose MCC</option>
            <?php
            foreach($mcc_list as $key=>$value) {
                echo "<option value='".$key."'";
                if($key == $onb_mcc)
                    echo " selected";
                echo ">".$value."</option>";
            }
            ?>
        </select>
        <div class="col-3">
            <button class="form-control btn btn-success col-3" data-href="limits">Confirm</button>
        </div>
    </div>
    <div class="step2 row limits">
        <h2>Payment Limits</h2>
        <p>What is the largest size a basket is expected to be in your local currency:</p>
        <select name="basket_limit" id="basket_limit" class="form-control" >
            <?php
            foreach($basket_limits as $value) {
                echo '<option value="'.$value.'"'; if((int)$onb_basket_limits===$value) { echo "selected"; } echo '>Less than '.number_format($value,0,",",".").'</option>';
            }
            ?>
        </select>
        <div class="col-3">
            <button class="form-control btn btn-success col-3" data-href="timeframes">Confirm</button>
        </div>
    </div>
    <div class="step2 row timeframes">
        <h2>How fast do you generally deliver your goods</h2>
        <input type="text" id="delivery_timeframe" name="delivery_timeframe" value="<?php echo $onb_delivery_timeframe; ?>">
        <div class="col-3">
            <button class="form-control btn btn-success col-3" data-group="contract" data-href="our_contract">Confirm</button>
        </div>
    </div>
</div>
