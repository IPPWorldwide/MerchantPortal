<?php
include("../../b.php");

if(isset($REQ["id"])) {
    $partner->UpdateSubscriptionPlan($REQ);
}

$invoice = $partner->SubscriptionPlanData($REQ["id"]);

echo head();
?>
        <form action="?" method="POST" class="form">
            <h2>Invoice Plan Data</h2>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">ID:<br /><input name="id" class="form-control" value="<?php echo $invoice->id; ?>" readonly></div>
            </div>
            <h2>Partner Details</h2>
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

            <div class="row row-cols-md-1 mb-1">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mb-3">Save</button>
                </div>

            </div>
        </form>
<?php

echo foot();