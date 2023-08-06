<?php
include("../../b.php");
if(isset($REQ["name"])) {
    $partner->AddSubscriptionPlan($REQ);
    header("Location: /partner/invoices/plans");
    die();
}

$partner_data = $partner->PartnerData();
echo head();
?>
        <form action="?" method="POST" class="form">
            <h2>Add New Invoicing Plan</h2>
            <div class="row row-cols-md-1 mb-1">
                <div class="col themed-grid-col">Plan Name:<br /><input name="name" class="form-control"></div>
                <div class="col themed-grid-col">Invoicing period:<br />
                    <select class="form-control" name="payment_period">
                        <option value="0">Daily</option>
                        <option value="1" selected>Monthly</option>
                        <option value="2">Quarterly</option>
                        <option value="3">Half Year</option>
                        <option value="4">Yearly</option>
                    </select>
                </div>
                <div class="col themed-grid-col">Currency:<br />
                    <select class="form-control" name="currency_id">
                        <?php
                        foreach($currency->currency_list() as $value) {
                            echo "<option value='$value' "; if($value===$IPP_CONFIG["CURRENCY"]) { echo "selected"; } echo ">".$currency->currency($value)[0]."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col themed-grid-col">Fixed Monthly Fee:<br /><input name="amount" class="form-control"></div>
                <div class="col themed-grid-col">Cost per transaction:<br /><input name="amount_tnx" class="form-control"></div>
                <div class="col themed-grid-col">Minimum cost per transaction:<br /><input name="amount_tnx_min" class="form-control"></div>
                <div class="col themed-grid-col">Acquiring Percentage %:<br /><input name="acquirer[percentage]" class="form-control"></div>
                <div class="col themed-grid-col">Acquiring Refund Cost:<br /><input name="acquirer[refund][cost]" class="form-control"></div>
                <div class="col themed-grid-col">Acquiring Refund Percentage %:<br /><input name="acquirer[refund][percentage]" class="form-control"></div>
                <div class="col themed-grid-col">Acquiring CFT Cost:<br /><input name="acquirer[cft][cost]" class="form-control"></div>
                <div class="col themed-grid-col">Acquiring CFT Percentage:<br /><input name="acquirer[cft][percentage]" class="form-control"></div>
                <div class="col themed-grid-col">Acquiring CBK Fee, below 1%:<br /><input name="acquirer[cbk][below]" class="form-control"></div>
                <div class="col themed-grid-col">Acquiring CBK Visa (1%-2%), MC (1%-1.5%):<br /><input name="acquirer[cbk][tier1]" class="form-control"></div>
                <div class="col themed-grid-col">Acquiring CBK Visa (above 2%), MC (above 1.5%):<br /><input name="acquirer[cbk][tier2]" class="form-control"></div>
                <div class="col themed-grid-col">Acquiring CBK Re-presentment Fee :<br /><input name="acquirer[cbk][representment]" class="form-control"></div>
                <div class="col themed-grid-col">Acquiring CBK Retrieval Request Fee:<br /><input name="acquirer[cbk][retrieval]" class="form-control"></div>
                <div class="col themed-grid-col">Acquiring Wire transfer fee:<br /><input name="acquirer[wire][fee]" class="form-control"></div>
            </div>
            <div class="row row-cols-md-1 mb-1">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mb-3">Create</button>
                </div>
            </div>
        </form>
<?php

echo foot();
