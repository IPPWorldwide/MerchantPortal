<?php
include_once "../base.php";
if(isset($REQ["name"])) {
    if(isset($REQ["rights"]["ALL"])) {
        $rights = $REQ["rights"]["ALL"];
    } else {
        $rights = implode(",",$REQ["rights"]);
    }
    $data = $ipp->AddAccessRight($REQ["name"],$rights);
    header("Location: /company_access_rights");
    die();
}
echo head();
$access_rights = $ipp->GetAllAccessRights()->content->all_rules;
echo '
    <div class="row">
        <div class="col-6">
            <h2>'.$lang["COMPANY"]["ACCESS_RIGHTS_ADD"]["HEADER"].'</h2>
        </div>
    </div>
<div class="row">
    <form action="?" method="POST" class="form">
        <div class="row row-cols-md-1 mb-1">
            <div class="col themed-grid-col">'.$lang["COMPANY"]["ACCESS_RIGHTS_ADD"]["NAME"].'<br /><input name="name" type="text" class="form-control"></div>
        </div>
        ';
        foreach($access_rights as $key=>$value) {
            echo '
        <div class="row row-cols-md-1 mb-1">
            <div class="col themed-grid-col">
                <div class="form-check">
                    <input class="form-check-input access_rights" type="checkbox" value="'.$value->id.'" id="checkbox_right_'.$value->id.'" name="rights['.$value->id.']">
                    <label class="form-check-label" for="checkbox_right_'.$value->id.'">
                        '.$value->description.'
                    </label>
                </div>
            </div>
        </div>';
        }
        echo '
        <div class="row row-cols-md-1 mb-1">
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary mb-3">'.$lang["COMPANY"]["ACCESS_RIGHTS_ADD"]["ADD_NEW"].'</button>
            </div>
        </div>
    </form>
</div>
';
echo foot(); ?>
