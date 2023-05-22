<?php
function html_preson($id,$full_name,$address = "",$postal = "",$city = "",$country = "") {
    return '
<div id="person_'.md5($full_name).'">
    <div class="mb-12 row">
        <div class="col-sm-12">
            <h3>'.$full_name.'</h3>
        </div>
    </div>
    <div class="mb-12 row">
        <label for="staticEmail" class="col-sm-2 col-form-label">Passport</label>
        <div class="col-sm-10">
            <input type="file" class="form-control input" id="passport">
        </div>
    </div>
    <div class="mb-12 row">
        <label for="staticEmail" class="col-sm-2 col-form-label">Utility Bill</label>
        <div class="col-sm-10">
            <input type="file" class="form-control input" id="passport">
        </div>
    </div>
</div>
';
}
