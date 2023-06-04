<?php
function html_preson($id,$full_name,$email="", $address = "",$postal = "",$city = "",$country = "", $files_passport= "", $files_driving_license_front="", $files_driving_license_back="", $files_address="") {
    global $IPP_CONFIG;
    $html = '
<div id="person_'.md5($full_name).'" data-md5="'.md5($full_name).'" data-id="'.$id.'">
    <div class="mb-12 row">
        <div class="col-sm-1 delete_person">
            <img src="'.$IPP_CONFIG["PORTAL_URL"].'assets/img/delete.png">
        </div>
        <div class="col-sm-11">
            <h3>'.$full_name.'</h3>
        </div>
    </div>
    <div class="mb-12 row">
        <label for="staticEmail" class="col-sm-2 col-form-label">Passport</label>
        <div class="col-sm-10">
        ';
            if($files_passport !== "") {
                $html .=    "Received";
            } else {
                $html .= "<input type='file' class='form-control input passport'>";
            }
            $html .= '
        </div>
    </div>
    <div class="mb-12 row">
        <label for="staticEmail" class="col-sm-2 col-form-label">Utility Bill</label>
        <div class="col-sm-10">
        ';
        if($files_address !== "") {
            $html .= "Received";
        } else {
            $html .= "<input type='file' class='form-control input address'>";
        }
    $html .= '
        </div>
    </div>
    <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
        <div class="col-sm-10">
            <input type="text" class="form-control input email" value="'.$email.'">
        </div>
    </div>
</div> ';

return $html;
}
