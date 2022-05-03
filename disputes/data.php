<?php
include("../base.php");
$dispute_id   = $REQ["id"];
$dispute_data = $ipp->DisputesData($dispute_id);

var_dump($dispute_data);

echo head();
?>
    <form action="?" method="POST" class="form">
        <h2>Dispute Data</h2>
        <div class="row row-cols-md-3 mb-3">
            <div class="col themed-grid-col">ID:<br /><input name="id" class="form-control" value="<?php echo $dispute_data->id; ?>" readonly></div>
        </div>
        <h2>Transaction</h2>
        <div class="row row-cols-md-3 mb-3">
            <div class="col themed-grid-col">ID:<br /><input name="id" class="form-control" value="<?php echo $dispute_data->transaction->id; ?>" readonly></div>
            <div class="col themed-grid-col">Amount:<br /><input name="id" class="form-control" value="<?php echo $dispute_data->transaction->amount_readable; ?>" readonly></div>
            <div class="col themed-grid-col">Currency:<br /><input name="id" class="form-control" value="<?php echo $dispute_data->transaction->currency->id; ?>" readonly></div>
        </div>

        <div class="row row-cols-md-1 mb-1">
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary mb-3">Save</button>
            </div>

        </div>
    </form>
<?php
echo foot();