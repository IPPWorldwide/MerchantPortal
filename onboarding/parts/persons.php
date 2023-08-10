<?php
function html_preson($id,$full_name,$email="", $address = "",$postal = "",$city = "",$country = "", $files_passport= "", $files_driving_license_front="", $files_driving_license_back="", $files_address="") {
    global $IPP_CONFIG, $lang;
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
        <label for="staticPassport" class="col-sm-2 col-form-label">'.$lang["COMPANY"]["ONBOARDING"]["PERSON_PASSPORT"].'</label>
        <div class="col-sm-10">
        ';
            if($files_passport !== "") {
                $html .= $lang["COMPANY"]["ONBOARDING"]["RECEIVED"];
            } else {
                $html .= "<input type='file' class='form-control input passport'>";
            }
            $html .= '
        </div>
    </div>
    <div class="mb-12 row">
        <label for="staticUtilitybill" class="col-sm-2 col-form-label">'.$lang["COMPANY"]["ONBOARDING"]["PERSON_UTILITY_BILL"].'</label>
        <div class="col-sm-10">
        ';
        if($files_address !== "") {
            $html .= $lang["COMPANY"]["ONBOARDING"]["RECEIVED"];
        } else {
            $html .= "<input type='file' class='form-control input address'>";
        }
    $html .= '
        </div>
    </div>
    <div class="mb-12 row">
        <label for="staticDrivinglicenseFront" class="col-sm-2 col-form-label">'.$lang["COMPANY"]["ONBOARDING"]["PERSON_DRIVING_LICENSE_FRONT"].'</label>
        <div class="col-sm-10">
        ';
    if($files_driving_license_front !== "") {
        $html .= $lang["COMPANY"]["ONBOARDING"]["RECEIVED"];
    } else {
        $html .= "<input type='file' class='form-control input driving_license_front'>";
    }
    $html .= '
        </div>
    </div>
    <div class="mb-12 row">
        <label for="staticDrivinglicenseBack" class="col-sm-2 col-form-label">'.$lang["COMPANY"]["ONBOARDING"]["PERSON_DRIVING_LICENSE_BACK"].'</label>
        <div class="col-sm-10">
        ';
    if($files_driving_license_back !== "") {
        $html .= $lang["COMPANY"]["ONBOARDING"]["RECEIVED"];
    } else {
        $html .= "<input type='file' class='form-control input driving_license_back'>";
    }
    $html .= '
        </div>
    </div>
    <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">'.$lang["COMPANY"]["ONBOARDING"]["PERSON_EMAIL"].'</label>
        <div class="col-sm-10">
            <input type="text" class="form-control input email" value="'.$email.'">
        </div>
    </div>
</div>';

return $html;
}
