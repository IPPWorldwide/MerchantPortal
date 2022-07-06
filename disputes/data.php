<?php
include("../base.php");

if(isset($REQ["documentation_type"])) {
    $added_documentation = $ipp->DisputesUpload($REQ["id"],$REQ["documentation_type"],$_FILES['attached_file']);
    header("Location: data.php?id=".$REQ["id"]."&documentation=".$added_documentation->success);
    exit;
}

$dispute_id   = $REQ["id"];
$dispute_data = $ipp->DisputesData($dispute_id);
echo head();
?>
        <h2>Dispute Data</h2>
        <div class="row row-cols-md-3 mb-3">
            <div class="col themed-grid-col">Dispute ID:<br /><input name="id" class="form-control" value="<?php echo $dispute_data->id; ?>" readonly></div>
            <div class="col themed-grid-col">Reason:<br /><input name="id" class="form-control" value="<?php echo $dispute_data->dispute_data->reason; ?>" readonly></div>
            <div class="col themed-grid-col">Reason Code:<br /><input name="id" class="form-control" value="<?php echo $dispute_data->dispute_data->text; ?>" readonly></div>
        </div>
        <h2>Transaction</h2>
        <div class="row row-cols-md-3 mb-3">
            <div class="col themed-grid-col">ID:<br /><input name="id" class="form-control" value="<?php echo $dispute_data->transaction->id; ?>" readonly></div>
            <div class="col themed-grid-col">Amount:<br /><input name="id" class="form-control" value="<?php echo $dispute_data->transaction->amount_readable; ?>" readonly></div>
            <div class="col themed-grid-col">Currency:<br /><input name="id" class="form-control" value="<?php echo $dispute_data->transaction->currency->id; ?>" readonly></div>
        </div>
        <div class="row row-cols-md-3 mb-3">
            <div class="col themed-grid-col">Order ID:<br /><input name="id" class="form-control" value="<?php echo $dispute_data->order_data->order_id; ?>" readonly></div>
        </div>
        <div class="row row-cols-md-3 mb-3">
            <h2>Uploaded documentation</h2>
            <div class="col themed-grid-col">
                <ul>
                    <?php
                    foreach($dispute_data->files as $files)
                        echo "<li>".$files->name."</li>";
                    ?>
                </ul>
            </div>
        </div>
        <hr />
        <form method="POST" enctype="multipart/form-data">
            <h2>Provide new documentation</h2>
            <input name="id" type="HIDDEN" value="<?php echo $dispute_data->id; ?>">
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">Documentation Type:
                    <select name="documentation_type" class="form-control">
                        <option value="order_confirmation">Order Confirmation</option>
                        <option value="shipping_documentation">Shipping documentation</option>
                        <option value="other_documentation">Other documentation</option>
                    </select>
                </div>
            </div>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">Documentation File:<br /><input name="attached_file" type="file" class="form-control"></div>
            </div>
            <div class="row row-cols-md-3 mb-3">
                <button type="submit" class="btn btn-primary mb-3">Attach documentation</button>
            </div>
        </form>
<?php
echo foot();