<?php
include("../b.php");

if(isset($REQ["revalidate"])) {
    $partner->ValidateDomains($REQ);
}

$partner_data = $partner->PartnerData();


echo head();
$actions->get_action("partner_domains");

?>
        <form action="?" method="POST" class="form">
            <h2>Domains</h2>
            <div class="row row-cols-md-1 mb-1">
                <div class="col themed-grid-col">
                    <table class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>URL</th>
                            <th>Github Username</th>
                            <th>Github Password</th>
                            <th>Github Repo</th>
                            <th>TXT Record</th>
                            <th>A Record</th>
                            <th>Verified TXT</th>
                            <th>Verified A</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($partner_data->domains as $key=>$value) {
                            $readonly = ((int)$value->verified == 1) ? "readonly" : "";
                            echo "<tr>";
                            echo "<td>".$value->id."</td>";
                            echo "<td><input type='text' name='domain[$value->id]' class='form-control' value='".$value->name."' $readonly></td>";
                            echo "<td><input type='text' name='github[$value->id][username]' class='form-control' value='".$value->github->username."'></td>";
                            echo "<td><input type='text' name='github[$value->id][token]' class='form-control' value='".$value->github->token."'></td>";
                            echo "<td><input type='text' name='github[$value->id][domain]' class='form-control' value='".$value->github->repo."'></td>";
                            echo "<td>".$value->validation->txt->record."</td>";
                            echo "<td>".$value->validation->a->record."</td>";
                            echo "<td>".$value->validation->txt->verified."</td>";
                            echo "<td>".$value->validation->a->verified."</td>";
                            echo "<td>".$value->status."</td>";
                            echo "</tr>";
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row row-cols-md-1 mb-1">
                <div class="d-flex justify-content-end">
                    <button type="submit" name="revalidate" class="btn btn-primary mb-3">Re-validate</button>
                </div>

            </div>
        </form>
<?php

echo foot();
