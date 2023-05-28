<?php
$iban = $onboarding_data->{"bank-iban"} ?? "";
$swift = $onboarding_data->{"bank-bic"} ?? "";
$settlement_schedule = $onboarding_data->{"bank-settlement"} ?? "weekly";
$earlier_processing = $onboarding_data->{"earlier_processing"} ?? "no";
$settlement = [
    "weekly",
    "daily",
    "monthly"
];

?>
<div id='financial'>
    <div class="step1 row settlement">
        <h3>Settlement account</h3>
        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">IBAN</label>
            <div class="col-sm-10">
                <input type="text" class="form-control input" id="iban" value="<?php echo $iban; ?>">
            </div>
        </div>
        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">SWIFT</label>
            <div class="col-sm-10">
                <input type="text" class="form-control input" id="swift" value="<?php echo $swift; ?>">
            </div>
        </div>
        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Screenshot from Bank</label>
            <div class="col-sm-10">
                <input type="file" class="form-control input" id="bank-screenshot">
            </div>
            <div class="alert alert-warning">
                The Screnshot must contain:<br />
                <ol>
                    <li>Logo or name of bank</li>
                    <li>IBAN</li>
                    <li>SWIFT</li>
                    <li>Account holder (Your company name)</li>
                    <li>Date</li>
                </ol>
            </div>
        </div>
        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Settlement Frequency</label>
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
            <button class="form-control btn btn-success col-3" data-group="financial" data-href="earlier_provider">Confirm</button>
        </div>
    </div>
    <div class="step2 row earlier_provider">
        <h2>Earlier Processing provider</h2>
        <p>We do collect data on your earlier payment provider. We do not ask why you want to change, but we need to see a copy of earlier processing.</p>
        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Have you had an earlier provider</label>
            <div class="col-sm-10">
                <select class="form-control select" id="earlierProcessing">
                    <option value="no" <?php if($earlier_processing === "no") { echo "selected"; } ?>>No</option>
                    <option value="yes" <?php if($earlier_processing === "yes") { echo "selected"; } ?>>Yes</option>
                </select>
            </div>
        </div>
        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Copy of processing history (3 months)</label>
            <div class="col-sm-10">
                <input type="file" class="form-control input" name="processing-history" id="processing-history">
            </div>
        </div>
        <div class="col-3">
            <button class="form-control btn btn-success col-3" data-group="website" data-href="website_check">Confirm</button>
        </div>
    </div>
</div>
