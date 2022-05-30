<?php
include("../b.php");

$invoice = $partner->InvoiceData($REQ["id"]);

echo head();

?>
<div class="py-5 text-center">
    <h2>Invoice</h2>
</div>
<div class="row g-5">
    <div class="col-md-5 col-lg-4 order-md-last">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-primary">Dates</span>
        </h4>
        <div class="col-md-12">
            <table class="table table-striped table-sm">
                <tbody>
                <tr>
                    <td>Issuing date</td>
                    <td><?php echo date("Y-m-d", $invoice->dates->issuing_date); ?></td>
                </tr>
                <tr>
                    <td>Period start</td>
                    <td><?php echo date("Y-m-d", $invoice->dates->period_start); ?></td>
                </tr>
                <tr>
                    <td>Period end</td>
                    <td><?php echo date("Y-m-d", $invoice->dates->period_end); ?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-primary">Purchased Products</span>
            <span class="badge bg-primary rounded-pill"><?php echo count($invoice->products) ?></span>
        </h4>
        <ul class="list-group mb-3">
            <?php

            foreach($invoice->products as $value) {
                echo "<li class=\"list-group-item d-flex justify-content-between lh-sm\">
                <div>
                    <h6 class=\"my-0\">".$value->name."</h6>
                    <small class=\"text-muted\">".$value->subname."</small>
                </div>
                <span class=\"text-muted\">".$value->price."</span>
            </li>";
            }
            ?>
        </ul>
    </div>
    <div class="col-md-7 col-lg-8">
        <h4 class="mb-3">Billing</h4>
        <form class="needs-validation" novalidate>
            <div class="row g-3">
                <div class="col-sm-6">
                    <label for="firstName" class="form-label">Company Name</label>
                    <div><?php echo $invoice->billing->company_name ?></div>
                </div>

                <div class="col-sm-6">
                    <label for="lastName" class="form-label">VAT</label>
                    <div><?php echo $invoice->billing->vat ?></div>
                </div>

                <div class="col-12">
                    <label for="address" class="form-label">Address</label>
                    <div><?php echo $invoice->billing->address ?></div>
                </div>

                <div class="col-md-4">
                    <label for="zip" class="form-label">Postal</label>
                    <div><?php echo $invoice->billing->postal ?></div>
                </div>
                <div class="col-md-4">
                    <label for="zip" class="form-label">City</label>
                    <div><?php echo $invoice->billing->city ?></div>
                </div>
                <div class="col-md-4">
                    <label for="country" class="form-label">Country</label>
                    <div><?php echo $invoice->billing->country ?></div>
                </div>
            </div>
        </form>
    </div>
</div>
