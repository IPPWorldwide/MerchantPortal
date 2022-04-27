<?php
include("../base.php");
if(isset($REQ["email"])) {
    $data = $ipp->AddUser($REQ);
    var_dump($data);
    die();
    header("Location: /users");
    die();
}
echo head();
?>
        <form action="?" method="POST" class="form">
            <input name="access_id" placeholder="For later usage" type="hidden">
            <h2>Add New User</h2>
            <div class="row row-cols-md-1 mb-1">
                <div class="col themed-grid-col">Name:<br /><input name="name" type="text" class="form-control"></div>
            </div>
            <div class="row row-cols-md-1 mb-1">
                <div class="col themed-grid-col">Email:<br /><input name="email" type="email" class="form-control"></div>
            </div>
            <div class="row row-cols-md-1 mb-1">
                <div class="col themed-grid-col">Phone no:<br /><input name="phone" type="tel" class="form-control"></div>
            </div>
            <div class="row row-cols-md-1 mb-1">
                <div class="col themed-grid-col">Password:<br /><input name="password" type="password" class="form-control"></div>
            </div>
            <div class="row row-cols-md-1 mb-1">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mb-3">Create User</button>
                </div>
            </div>
        </form>
<?php

echo foot();