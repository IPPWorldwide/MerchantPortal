<?php
$bank_name = $onboarding_data->{"bank-name"} ?? "";
$iban = $onboarding_data->{"bank-iban"} ?? "";
$swift = $onboarding_data->{"bank-bic"} ?? "";
$settlement_schedule = $onboarding_data->{"bank-settlement"} ?? "weekly";
$earlier_processing = $onboarding_data->{"earlier_processing"} ?? "no";
$processing_history = $onboarding_data->{"processing-history"} ?? "";
$bank_documentation = $onboarding_data->{"bank-documentation"} ?? "";
$bank_account_currency_code = $onboarding_data->{"bank-account-currency-code"} ?? "EUR";
$settlement = [
    "weekly",
    "daily",
    "monthly"
];
?>
<div id='financial'>
    <div class="step1 row settlement">
        <h3><?php echo $lang["COMPANY"]["ONBOARDING"]["SETTLEMENT_ACCOUNT"] ?></h3>
        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label"><?php echo $lang["COMPANY"]["ONBOARDING"]["NAME_OF_BANK"] ?></label>
            <div class="col-sm-10">
                <input type="text" class="form-control input" id="bank-name" value="<?php echo $bank_name; ?>">
            </div>
        </div>
        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label"><?php echo $lang["COMPANY"]["ONBOARDING"]["IBAN"] ?></label>
            <div class="col-sm-10">
                <input type="text" class="form-control input" id="iban" value="<?php echo $iban; ?>">
            </div>
        </div>
        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label"><?php echo $lang["COMPANY"]["ONBOARDING"]["SWIFT"] ?></label>
            <div class="col-sm-10">
                <input type="text" class="form-control input" id="swift" value="<?php echo $swift; ?>">
            </div>
        </div>
        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label"><?php echo $lang["COMPANY"]["ONBOARDING"]["SETTLEMENT_CURRENCY"] ?></label>
            <div class="col-sm-10">
                <select class="form-control input" id="bank-account-currency-code" >
                    <?php
                    foreach($currency->currency_list() as $value) {
                        echo "<option";
                        if($currency->currency($value)[0] === $bank_account_currency_code)
                            echo " selected ";
                        echo ">".$currency->currency($value)[0]."</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label"><?php echo $lang["COMPANY"]["ONBOARDING"]["SCREENSHOT_FROM_BANK"] ?></label>
            <div class="col-sm-10">
                <input type="file" class="form-control input" id="bank-screenshot">
                <?php
                    if($bank_documentation !== "") {
                        echo  $lang["COMPANY"]["ONBOARDING"]["SCREENSHOT_ALREADY_UPLOADED"];
                    }
                ?>
            </div>
            <div class="alert alert-warning">
                <?php echo $lang["COMPANY"]["ONBOARDING"]["SCREENSHOT_DOCUMENTATION"] ?>
            </div>
        </div>
        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label"><?php echo $lang["COMPANY"]["ONBOARDING"]["SETTLEMENT_FREQUENCY"] ?></label>
            <div class="col-sm-10">
                <select class="form-control select" id="settlementFrequency">
                    <?php
                        foreach($settlement as $value) {
                            echo "<option value='".strtolower($value)."' "; if($settlement_schedule === strtolower($value)) { echo "selected"; } echo ">".ucfirst($value)."</option>";
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-3">
            <button class="form-control btn btn-success col-3" data-group="financial" data-href="earlier_provider"><?php echo $lang["COMPANY"]["ONBOARDING"]["CONFIRM"] ?></button>
        </div>
    </div>
    <div class="step2 row earlier_provider">
        <h2><?php echo $lang["COMPANY"]["ONBOARDING"]["SETTLEMENT_EARLIER_PROVIDER"] ?></h2>
        <p><?php echo $lang["COMPANY"]["ONBOARDING"]["SETTLEMENT_EARLIER_PROVIDER_EXPLAINER"] ?></p>
        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label"><?php echo $lang["COMPANY"]["ONBOARDING"]["SETTLEMENT_EARLIER_PROVIDER_NEW"] ?></label>
            <div class="col-sm-10">
                <select class="form-control select" id="earlierProcessing">
                    <option value="no" <?php if($earlier_processing === "no") { echo "selected"; } ?>><?php echo $lang["NO"] ?></option>
                    <option value="yes" <?php if($earlier_processing === "yes") { echo "selected"; } ?>><?php echo $lang["YES"] ?></option>
                </select>
            </div>
        </div>
        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label"><?php echo $lang["COMPANY"]["ONBOARDING"]["SETTLEMENT_COPY_OF_PROCESSING"] ?></label>
            <div class="col-sm-10">
                <input type="file" class="form-control input" name="processing-history" id="processing-history">
                <?php
                if($processing_history !== "") {
                    echo $lang["COMPANY"]["ONBOARDING"]["SCREENSHOT_ALREADY_UPLOADED"];
                }
                ?>
            </div>
        </div>
        <div class="col-3">
            <button class="form-control btn btn-success col-3" data-group="website" data-href="website_check"><?php echo $lang["COMPANY"]["ONBOARDING"]["CONFIRM"] ?></button>
        </div>
    </div>
</div>
