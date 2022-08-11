<?php
include("../b.php");
if(isset($REQ["company"])) {
    $data = $partner->AddMerchant($REQ);
    header("Location: /partner/companies");
    die();
}

$partner_data = $partner->PartnerData();
echo head();
?>
        <form action="?" method="POST" class="form">
            <h2>Add New Company</h2>
            <div class="row row-cols-md-1 mb-1">
                <div class="col themed-grid-col">Company Name:<br /><input name="company[name]" class="form-control" required></div>
            </div>
            <div class="row row-cols-md-1 mb-1">
                <div class="col themed-grid-col">Contact name:<br /><input name="contact[name]" class="form-control" required></div>
                <div class="col themed-grid-col">Contact Phone:<br /><input name="contact[phone]" pattern="[0-9]{1,25}" required type="tel" class="form-control"></div>
                <div class="col themed-grid-col">Contact Email:<br /><input name="contact[email]" pattern=".+@.+" required type="email" class="form-control"></div>
            </div>
            <div class="row row-cols-md-1 mb-1">
                <div class="col themed-grid-col">Password:<br /><input name="contact[password]" class="form-control" required></div>
            </div>
            <div class="row row-cols-md-1 mb-1">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mb-3">Create Company</button>
                </div>
            </div>
        </form>
<?php

echo foot();