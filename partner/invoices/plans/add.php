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
                        <option value="zzMq-stjY-qKx1">DKK</option>
                        <option value="PW1a-Ru1v-LCxL" selected>EUR</option>
                        <option value="KNKH-hzjJ-N7Qs">USD</option>
                        <option value="iEI0-sn0A-P5a1">NOK</option>
                        <option value="DVeG-lcB7-pCRn">SEK</option>
                    </select>
                </div>
                <div class="col themed-grid-col">Fixed Monthly Fee:<br /><input name="amount" class="form-control"></div>
                <div class="col themed-grid-col">Cost per transaction:<br /><input name="amount_tnx" class="form-control"></div>
            </div>
            <div class="row row-cols-md-1 mb-1">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mb-3">Create</button>
                </div>
            </div>
        </form>
<?php

echo foot();