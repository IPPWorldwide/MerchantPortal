<?php
include("../b.php");

if(isset($REQ["submit"])) {
    $partner->UpdateData($REQ);
}
$partner_data = $partner->PartnerData();

echo head();
?>
        <form action="?" method="POST" class="form">
            <h2>Partner Data</h2>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">Partner ID:<br /><input name="id" class="form-control" value="<?php echo $partner_data->id; ?>" readonly></div>
                <div class="col themed-grid-col">Key 1:<br /><input name="security[key1]" class="form-control" value="<?php echo $partner_data->security->key1; ?>"></div>
                <div class="col themed-grid-col">Key 2:<br /><input name="security[key2]" class="form-control" value="<?php echo $partner_data->security->key2; ?>"></div>
            </div>
            <h2>Partner Details</h2>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">Partner Name:<br /><input name="meta[name]" class="form-control" value="<?php echo isset($partner_data->meta_data->name) ? $partner_data->meta_data->name : ""; ?>"></div>
            </div>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">Country:<br />
                    <select name="meta[country]" class="form-control" >
                        <?php foreach($partner->ListCountry() as $key=>$value) {
                            echo "<option value='".$value->id."' ";
                            echo (isset($partner_data->country->id) && $partner_data->country->id == $value->id) ? "selected" : "";
                            echo ">".$value->name."</option>";
                        } ?>
                    </select>
                    </div>
            </div>
            <h2>Partner Invoices</h2>
            <div class="row row-cols-md-12 mb-12">
                <div class="col themed-grid-col"Invoice text:<br /><input name="meta[invoicetext]" class="form-control" value="<?php echo isset($partner_data->meta_data->meta->invoicetext) ? $partner_data->meta_data->meta->invoicetext : ""; ?>">
            </div>
            <div class="row row-cols-md-2 mb-2">
                <div class="col themed-grid-col">
                    <h2>Acquirers</h2>
                    <table class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>ID</th>
                            <th>Website</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($partner_data->acquirers as $key=>$value) {
                            echo "<tr><td>".$value->name."</td><td>".$value->id."</td><td></td><td><a href='".$value->url."' target='_BLANK'>".$value->url."</a></td></tr>";
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row row-cols-md-1 mb-1">
                <div class="d-flex justify-content-end">
                    <button type="submit" name="submit" class="btn btn-primary mb-3">Save</button>
                </div>
            </div>
        </form>
<?php

echo foot();