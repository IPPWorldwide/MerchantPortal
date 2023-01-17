<?php
include("../../b.php");

if(isset($REQ["id"]) && isset($REQ["name"])) {
    $partner->UpdateSubscriptionPlan($REQ);
    header("Location: details.php?id=".$REQ["id"]);
    die();
}

$invoice = $partner->SubscriptionPlanData($REQ["id"]);

echo head();
?>
        <form action="?" method="POST" class="form">
            <h2>Invoice Plan Data</h2>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">ID:<br /><input name="id" class="form-control" value="<?php echo $invoice->id; ?>" readonly></div>
            </div>
            <h2>Invoice Plan Details</h2>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">Name:<br /><input name="name" class="form-control" value="<?php echo $invoice->name; ?>"></div>
            </div>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">
                    Invoicing period:<br />
                    <select class="form-control" name="payment_period">
                        <option value="0" <?php echo ($invoice->period == 0) ? "selected" : "" ?>>Daily</option>
                        <option value="1" <?php echo ($invoice->period == 1) ? "selected" : "" ?>>Monthly</option>
                        <option value="2" <?php echo ($invoice->period == 2) ? "selected" : "" ?>>Quarterly</option>
                        <option value="3" <?php echo ($invoice->period == 3) ? "selected" : "" ?>>Half Year</option>
                        <option value="4" <?php echo ($invoice->period == 4) ? "selected" : "" ?>>Yearly</option>
                    </select>
                </div>
            </div>

            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">
                    Currency:
                    <select class="form-control" name="currency_id">
                        <option value="zzMq-stjY-qKx1" <?php echo ($invoice->currency == "zzMq-stjY-qKx1") ? "selected" : "" ?>>DKK</option>
                        <option value="PW1a-Ru1v-LCxL" <?php echo ($invoice->currency == "PW1a-Ru1v-LCxL") ? "selected" : "" ?>>EUR</option>
                        <option value="KNKH-hzjJ-N7Qs" <?php echo ($invoice->currency == "KNKH-hzjJ-N7Qs") ? "selected" : "" ?>>USD</option>
                        <option value="iEI0-sn0A-P5a1" <?php echo ($invoice->currency == "iEI0-sn0A-P5a1") ? "selected" : "" ?>>NOK</option>
                        <option value="DVeG-lcB7-pCRn" <?php echo ($invoice->currency == "DVeG-lcB7-pCRn") ? "selected" : "" ?>>SEK</option>
                    </select>
                </div>
            </div>


            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">
                    Fixed Monthly Fee:
                    <input name="amount" class="form-control" value="<?php echo $invoice->amount_readable; ?>">
                </div>
            </div>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">
                    Cost per transaction:
                    <input name="amount_tnx" class="form-control" value="<?php echo $invoice->amount_tnx_readable; ?>">
                </div>
            </div>

            <div class="col themed-grid-col">Acquiring Percentage %:<br /><input name="acquirer[percentage]" class="form-control" value="<?php echo number_format($invoice->acquirer_cost->tnx->percentage/100,2); ?>"></div>
            <div class="col themed-grid-col">Acquiring Refund Cost:<br /><input name="acquirer[refund][cost]" class="form-control" value="<?php echo number_format($invoice->acquirer_cost->refund->tnx_cost/100,2); ?>"></div>
            <div class="col themed-grid-col">Acquiring Refund Percentage %:<br /><input name="acquirer[refund][percentage]" class="form-control" value="<?php echo number_format($invoice->acquirer_cost->refund->percentage/100,2);; ?>"></div>
            <div class="col themed-grid-col">Acquiring CFT Cost:<br /><input name="acquirer[cft][cost]" class="form-control" value="<?php echo number_format($invoice->acquirer_cost->cft->tnx_cost/100,2); ?>"></div>
            <div class="col themed-grid-col">Acquiring CFT Percentage:<br /><input name="acquirer[cft][percentage]" class="form-control" value="<?php echo number_format($invoice->acquirer_cost->cft->percentage/100,2); ?>"></div>
            <div class="col themed-grid-col">Acquiring CBK Fee, below 1%:<br /><input name="acquirer[cbk][below]" class="form-control" value="<?php echo number_format($invoice->acquirer_cost->cbk->standard->fee/100,2); ?>"></div>
            <div class="col themed-grid-col">Acquiring CBK Visa (1%-2%), MC (1%-1.5%):<br /><input name="acquirer[cbk][tier1]" class="form-control" value="<?php echo number_format($invoice->acquirer_cost->cbk->tier1->fee/100,2); ?>"></div>
            <div class="col themed-grid-col">Acquiring CBK Visa (above 2%), MC (above 1.5%):<br /><input name="acquirer[cbk][tier2]" class="form-control" value="<?php echo number_format($invoice->acquirer_cost->cbk->tier2->fee/100,2); ?>"></div>
            <div class="col themed-grid-col">Acquiring CBK Re-presentment Fee :<br /><input name="acquirer[cbk][representment]" class="form-control" value="<?php echo number_format($invoice->acquirer_cost->cbk->other->represent/100,2); ?>"></div>
            <div class="col themed-grid-col">Acquiring CBK Retrieval Request Fee:<br /><input name="acquirer[cbk][retrieval]" class="form-control" value="<?php echo number_format($invoice->acquirer_cost->cbk->other->retrieval/100,2); ?>"></div>
            <div class="col themed-grid-col">Acquiring Wire transfer fee:<br /><input name="acquirer[wire][fee]" class="form-control" value="<?php echo number_format($invoice->acquirer_cost->wire->tnx_cost/100,2); ?>"></div>
            <div class="row row-cols-md-1 mb-1">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mb-3">Save</button>
                </div>

            </div>
        </form>
<?php

echo foot();
