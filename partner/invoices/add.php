<?php
include("../b.php");

if(isset($REQ["action"])) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($partner->MerchantData($REQ["company_id"]));
    die();
}

if(isset($REQ["add_invoice"])) {
    $data = $partner->AddInvoice($REQ);
    header("Location: /partner/invoices");
    die();
}
$partner_data = $partner->PartnerData();

echo head();
?>
<div class="py-5 text-center">
    <h2>Invoice</h2>
</div>
<form class="needs-validation" method="POST" novalidate>
    <input type="hidden" name="invoice_payment_slip" value="<?php echo $partner_data->meta_data->meta->invoicetext ?: "" ?>">
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
                    <td><input class="form-control" name="issuing[date]" value="<?php echo date("Y-m-d"); ?>"></td>
                </tr>
                <tr>
                    <td>Period start</td>
                    <td><input class="form-control" name="issuing[period_start]" value="<?php echo date("Y-m-d"); ?>"></td>
                </tr>
                <tr>
                    <td>Period end</td>
                    <td><input class="form-control" name="issuing[period_end]" value="<?php echo date("Y-m-d"); ?>"></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-12">
            <table class="table table-striped table-sm">
                <tbody>
                <tr>
                    <td>Currency</td>
                    <td><select class="form-control" name="currency">
                            <?php
                            foreach($currency->currency_list() as $value) {
                                echo "<option value='$value' "; if($value===$IPP_CONFIG["CURRENCY"]) { echo "selected"; } echo ">".$currency->currency($value)[0]."</option>";
                            }
                            ?>
                        </select></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-12">
        <h4 class="mb-3">Find Customer</h4>
        <div class="row g-3">
            <div class="col-sm-6">
                <select class="SelectCustomer form-control">
                    <option>-- SELECT CUSTOMER HERE --</option>
                    <?php
                    foreach($partner->ListCompany() as $value) {
                        echo "<option value='".$value->id."'>".$value->name."</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-7 col-lg-8">
        <h4 class="mb-3">Billing</h4>
            <input type="hidden" name="company_id" id="company_id">
            <div class="row g-3">
                <div class="col-sm-6">
                    <label for="firstName" class="form-label">Company Name</label>
                    <input class="form-control" name="company[name]" id="companyname">
                </div>

                <div class="col-sm-6">
                    <label for="lastName" class="form-label">VAT</label>
                    <input class="form-control" name="company[vat]" id="companyvat">
                </div>

                <div class="col-12">
                    <label for="address" class="form-label">Address</label>
                    <input class="form-control" name="address[address]" id="addressaddress">
                </div>

                <div class="col-md-4">
                    <label for="zip" class="form-label">Postal</label>
                    <input class="form-control" name="address[postal]" id="addresspostal">
                </div>
                <div class="col-md-4">
                    <label for="zip" class="form-label">City</label>
                    <input class="form-control" name="address[city]" id="addresscity">
                </div>
                <div class="col-md-4">
                    <label for="country" class="form-label">Country</label>
                    <input class="form-control" name="address[country]" id="addresscountry">
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <label for="zip" class="form-label">Product name</label>
                    <input class="form-control productdata" name="product[1][name]">
                </div>
                <div class="col-md-1">
                    <label for="zip" class="form-label">Qty</label>
                    <input type="number" class="form-control productdata" name="product[1][qty]">
                </div>
                <div class="col-md-3">
                    <label for="country" class="form-label">Price</label>
                    <input class="form-control productdata" name="product[1][price]">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" id="add_invoice" name="add_invoice" class="btn btn-primary mb-3" disabled="disabled">Create Invoice</button>
                </div>
            </div>
    </div>
</div>
</form>

<?php

echo foot();