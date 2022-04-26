<?php
include("../b.php");
if(isset($REQ["email"])) {
    $data = $partner->AddUser($REQ);
    header("Location: /partner/users");
    die();
}

$partner_data = $partner->PartnerData();
echo head();
?>
        <form action="?" method="POST" class="form">
            <h2>Add New User</h2>
            <div class="row row-cols-md-1 mb-1">
                <div class="col themed-grid-col">Email:<br /><input name="email" class="form-control"></div>
            </div>
            <div class="row row-cols-md-1 mb-1">
                <div class="col themed-grid-col">Password:<br /><input name="password" class="form-control"></div>
            </div>
            <div class="row row-cols-md-1 mb-1">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mb-3">Create User</button>
                </div>
            </div>
        </form>
<?php

echo foot();