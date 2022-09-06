<?php
include("../b.php");

if(isset($REQ["submit"])) {
    $partner->UpdateData($REQ,$REQ["meta"]["name"]);
}
$partner_data = $partner->PartnerData();
echo head();
echo '
        <form action="?" method="POST" class="form">
            <h2>'.$lang["PARTNER"]["DATA"]["HEADER"].'</h2>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">'.$lang["PARTNER"]["DATA"]["PARTNER_ID"].'<br /><input name="id" class="form-control" value="'.$partner_data->id.'" readonly></div>
                <div class="col themed-grid-col">'.$lang["PARTNER"]["DATA"]["KEY_1"].'<br /><input name="security[key1]" class="form-control" value="'.$partner_data->security->key1.'"></div>
                <div class="col themed-grid-col">'.$lang["PARTNER"]["DATA"]["KEY_2"].'<br /><input name="security[key2]" class="form-control" value="'.$partner_data->security->key2.'"></div>
            </div>
            <h2>'.$lang["PARTNER"]["DATA"]["DETAILS"].'</h2>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">'.$lang["PARTNER"]["DATA"]["NAME"].'<br /><input name="meta[name]" class="form-control" value="'; echo $partner_data->meta_data->name ?? ""; echo '"></div>
            </div>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">'.$lang["PARTNER"]["DATA"]["COUNTRY"].'<br />
                    <select name="meta[country]" class="form-control" >
                        ';
                        foreach($partner->ListCountry() as $key=>$value) {
                            echo "<option value='".$value->id."' ";
                            echo (isset($partner_data->country->id) && $partner_data->country->id == $value->id) ? "selected" : "";
                            echo ">".$value->name."</option>";
                        }
                        echo '
                    </select>
                    </div>
            </div>
            <div class="row row-cols-md-2 mb-2">
                <div class="col themed-grid-col">
                    <h2>'.$lang["PARTNER"]["DATA"]["ACQUIRERS"].'</h2>
                    <table class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                        <thead>
                        <tr>
                            <th>'.$lang["PARTNER"]["DATA"]["ACQUIRERS_NAME"].'</th>
                            <th>'.$lang["PARTNER"]["DATA"]["ACQUIRERS_ID"].'</th>
                            <th>'.$lang["PARTNER"]["DATA"]["ACQUIRERS_WEBSITE"].'</th>
                        </tr>
                        </thead>
                        <tbody>
                        ';
                        foreach($partner_data->acquirers as $key=>$value) {
                            echo "<tr><td>".$value->name."</td><td>".$value->id."</td><td></td><td><a href='".$value->url."' target='_BLANK'>".$value->url."</a></td></tr>";
                        }
                        echo '
                        </tbody>
                    </table>
                </div>
            </div>            
            <div class="row row-cols-md-1 mb-1">
                <div class="d-flex justify-content-end">
                    <button type="submit" name="submit" class="btn btn-primary mb-3">'.$lang["PARTNER"]["DATA"]["SAVE"].'</button>
                </div>
            </div>
        </form>
';
echo foot();