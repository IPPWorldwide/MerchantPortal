<?php
include("../base.php");
if(isset($REQ["email"])) {
    $data = $ipp->AddUser($REQ);
    header("Location: /users");
    die();
}
echo head();
echo '
<form action="?" method="POST" class="form">
    <input name="access_id" placeholder="For later usage" type="hidden">
    <h2>'.$lang["COMPANY"]["USERS_ADD"]["HEADER"].'</h2>
    <div class="row row-cols-md-1 mb-1">
        <div class="col themed-grid-col">'.$lang["COMPANY"]["USERS_ADD"]["NAME"].'<br /><input name="name" type="text" class="form-control"></div>
    </div>
    <div class="row row-cols-md-1 mb-1">
        <div class="col themed-grid-col">'.$lang["COMPANY"]["USERS_ADD"]["EMAIL"].'<br /><input name="email" type="email" class="form-control"></div>
    </div>
    <div class="row row-cols-md-1 mb-1">
        <div class="col themed-grid-col">'.$lang["COMPANY"]["USERS_ADD"]["PHONENO"].'<br /><input name="phone" type="tel" class="form-control"></div>
    </div>
    <div class="row row-cols-md-1 mb-1">
        <div class="col themed-grid-col">'.$lang["COMPANY"]["USERS_ADD"]["PASSWORD"].'<br /><input name="password" type="password" class="form-control"></div>
    </div>
    <div class="row row-cols-md-1 mb-1">
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary mb-3">'.$lang["COMPANY"]["USERS_ADD"]["CREATE_USER"].'</button>
        </div>
    </div>
</form>
';
echo foot();