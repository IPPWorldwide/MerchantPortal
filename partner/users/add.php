<?php
include("../b.php");
if(isset($REQ["email"])) {
    $data = $partner->AddUser($REQ);
    header("Location: /partner/users");
    die();
}

$partner_data = $partner->PartnerData();
echo head();
echo '
        <form action="?" method="POST" class="form">
            <h2>'.$lang["PARTNER"]["USERS_ADD"]["HEADER"].'</h2>
            <div class="row row-cols-md-1 mb-1">
                <div class="col themed-grid-col">'.$lang["PARTNER"]["USERS_ADD"]["EMAIL"].'<br /><input name="email" class="form-control"></div>
            </div>
            <div class="row row-cols-md-1 mb-1">
                <div class="col themed-grid-col">'.$lang["PARTNER"]["USERS_ADD"]["PASSWORD"].'<br /><input name="password" class="form-control"></div>
            </div>
            <div class="row row-cols-md-1 mb-1">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mb-3">'.$lang["PARTNER"]["USERS_ADD"]["CREATE_USER"].'</button>
                </div>
            </div>
        </form>
';
echo foot();