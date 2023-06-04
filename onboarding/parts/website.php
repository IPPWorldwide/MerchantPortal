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
$website_domain_name = $onboarding_data->website->{"url"} ?? "";

(bool)$identified_terms = $onboarding_data->website->terms->{"found"} ?? 0;
$terms_url = $onboarding_data->website->terms->{"url"} ?? "";
(bool)$identified_privacy_policy = $onboarding_data->website->privacy_policy->{"found"} ?? 0;
$privacy_policy_url = $onboarding_data->website->privacy_policy->{"url"} ?? "";

(bool)$identified_product = $onboarding_data->website->product->{"found"} ?? 0;
$product_url = $onboarding_data->website->product->{"url"} ?? "";

(bool)$identified_checkout_flow = $onboarding_data->website->checkout->{"found"} ?? 0;
$checkout_flow_url = $onboarding_data->website->checkout->{"url"} ?? "";

(bool)$identified_login_required = $onboarding_data->website->login->{"found"} ?? 0;
$login_url = $onboarding_data->website->login->{"url"} ?? "";
$login_username = $onboarding_data->website->login->{"username"} ?? "";
$login_password = $onboarding_data->website->privacy_policy->{"password"} ?? "";

(bool)$identified_lorem_ipsum = $onboarding_data->website->lorem_ipsum->{"found"} ?? 0;
$lorem_ipsum_url = $onboarding_data->website->lorem_ipsum->{"url"} ?? "";

?>
<div id='website'>
    <div class="step1 row website_check">
        <h2>Checking your website</h2>
        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">URL</label>
            <div class="col-sm-10 col-form-label website_check_url">
                <?php echo $website_domain_name; ?>
            </div>
        </div>
        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Terms & Conditions</label>
            <div class="col-sm-10 col-form-label">
                <?php
                if($identified_terms) {
                    echo "Terms was identified.";
                } else {
                    echo "Not identified.";
                }
                ?>
            </div>
            <label for="staticEmail" class="col-sm-2 col-form-label">Terms & Conditions URL</label>
            <div class="col-sm-10 col-form-label">
                <?php echo $terms_url; ?>
            </div>
        </div>

        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Privacy Policy</label>
            <div class="col-sm-10 col-form-label">
                <?php
                if($identified_privacy_policy) {
                    echo "Policy was identified.";
                } else {
                    echo "Not identified.";
                }
                ?>
            </div>
            <label for="staticEmail" class="col-sm-2 col-form-label">Privacy Policy URL</label>
            <div class="col-sm-10 col-form-label">
                <?php echo $privacy_policy_url; ?>
            </div>
        </div>

        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Checkout Flow</label>
            <div class="col-sm-10 col-form-label">
                <?php
                if($identified_checkout_flow) {
                    echo "Checkout was identified.";
                } else {
                    echo "Not identified.";
                }
                ?>
            </div>
            <label for="staticEmail" class="col-sm-2 col-form-label">Checkout Flow URL</label>
            <div class="col-sm-10 col-form-label">
                <?php echo $checkout_flow_url; ?>
            </div>
        </div>

        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Lorem Ipsum Check</label>
            <div class="col-sm-10 col-form-label">
                <?php
                if($identified_lorem_ipsum) {
                    echo "Checkout was identified.";
                } else {
                    echo "Not identified.";
                }
                ?>
            </div>
            <label for="staticEmail" class="col-sm-2 col-form-label">Lorem Ipsum URL</label>
            <div class="col-sm-10 col-form-label">
                <?php echo $lorem_ipsum_url; ?>
            </div>
        </div>


        <div class="col-6">
            <button class="form-control btn btn-success col-3 checkWebsite" disabled="disabled">Ask us to check Again</button>
        </div>
        <div class="col-6">
            <button class="form-control btn btn-success col-3 ValidatedWebsiteChecks" disabled="disabled" data-href="mcc">Confirm the list above is in place</button>
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
