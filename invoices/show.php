<?php
include_once("../base.php");

$invoice = $ipp->InvoiceData($REQ["id"]);

echo head();

echo '
<div class="py-5 text-center">
    <h2>'.$lang["COMPANY"]["INVOICES_SHOW"]["HEADER"].'</h2>
</div>
<div class="row g-5">
    <div class="col-md-5 col-lg-4 order-md-last">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-primary">'.$lang["COMPANY"]["INVOICES_SHOW"]["DATES"].'</span>
        </h4>
        <div class="col-md-12">
            <table class="table table-striped table-sm">
                <tbody>
                <tr>
                    <td>'.$lang["COMPANY"]["INVOICES_SHOW"]["ISSUING_DATE"].'</td>
                    <td>'; echo date("Y-m-d", $invoice->dates->issuing_date); echo '</td>
                </tr>
                <tr>
                    <td>'.$lang["COMPANY"]["INVOICES_SHOW"]["PERIOD_START"].'</td>
                    <td>'; echo date("Y-m-d", $invoice->dates->period_start); echo '</td>
                </tr>
                <tr>
                    <td>'.$lang["COMPANY"]["INVOICES_SHOW"]["PERIOD_END"].'</td>
                    <td>'; echo date("Y-m-d", $invoice->dates->period_end); echo '</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-primary">'.$lang["COMPANY"]["INVOICES_SHOW"]["PURCHASED_PRODUCTS"].'</span>
            <span class="badge bg-primary rounded-pill">'; echo count((array)$invoice->products); echo '</span>
        </h4>
        <ul class="list-group mb-3">
            ';
            foreach($invoice->products as $value) {
                echo "<li class=\"list-group-item d-flex justify-content-between lh-sm\">
                <div>
                    <h6 class=\"my-0\">".$value->name."</h6>
                    <small class=\"text-muted\">".$value->subname."</small>
                </div>
                <span class=\"text-muted\">".$value->price_readable."</span>
            </li>";
            }
           echo '
        </ul>
    </div>
    <div class="col-md-7 col-lg-8">
        <h4 class="mb-3">'.$lang["COMPANY"]["INVOICES_SHOW"]["BILLING"].'</h4>
        <form class="needs-validation" novalidate>
            <div class="row g-3">
                <div class="col-sm-6">
                    <label for="firstName" class="form-label">'.$lang["COMPANY"]["INVOICES_SHOW"]["COMPANY_NAME"].'</label>
                    <div>'; echo $invoice->billing->company_name; echo '</div>
                </div>

                <div class="col-sm-6">
                    <label for="lastName" class="form-label">'.$lang["COMPANY"]["INVOICES_SHOW"]["VAT"].'</label>
                    <div>'; echo $invoice->billing->vat; echo '</div>
                </div>

                <div class="col-12">
                    <label for="address" class="form-label">'.$lang["COMPANY"]["INVOICES_SHOW"]["ADDRESS"].'</label>
                    <div>'; echo $invoice->billing->address; echo '</div>
                </div>

                <div class="col-md-4">
                    <label for="zip" class="form-label">'.$lang["COMPANY"]["INVOICES_SHOW"]["POSTAL"].'</label>
                    <div>'; echo $invoice->billing->postal; echo '</div>
                </div>
                <div class="col-md-4">
                    <label for="zip" class="form-label">'.$lang["COMPANY"]["INVOICES_SHOW"]["CITY"].'</label>
                    <div>'; echo $invoice->billing->city; echo '</div>
                </div>
                <div class="col-md-4">
                    <label for="country" class="form-label">'.$lang["COMPANY"]["INVOICES_SHOW"]["COUNTRY"].'</label>
                    <div>'; echo $invoice->billing->country; echo '</div>
                </div>
            </div>
        </form>
    </div>
</div>
';
if(isset($invoice->billing->payment_slip) && $invoice->billing->payment_slip !== "") {
    echo "<div class='col-md-12 alert  alert-info'>
        ".$invoice->billing->payment_slip."</div>";
}
