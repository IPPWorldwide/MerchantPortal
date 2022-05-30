<?php
include("../base.php");
$merchant_data = $ipp->MerchantData();
if(isset($REQ["send_link"])) {
    $acquirer_data = array();
    $ipp->SendPaymentLink($REQ["sender"],$REQ["recipient"],$REQ["expiry_time"],$REQ["order_id"],$REQ["amount"],$REQ["currency"]);
    header("Location: ?sent=1");
    die();
}
echo head();
?>
      <h2>Payment Links</h2>
<?php
if(isset($REQ["sent"]) && $REQ["sent"] == 1) {
    echo "<div class=\"col md-12 alert-warning rounded text-muted alert\">Your payment link have been sent</div>";
}
?>
    <form action="?" method="POST">
        <div class="class="row row-cols-md-3 mb-3">
            <div class="col themed-grid-col">
                Sender:<br>
                <input name="sender" class="form-control" placeholder="Email of sender">
            </div>
            <div class="col themed-grid-col">
                Recipient:<br>
                <input name="recipient" class="form-control" placeholder="Email of receipient">
            </div>
            <div class="col themed-grid-col">
                Expiry Time for Link:<br>
                <input type="date" name="expiry_time" class="form-control" value="<?php echo date("Y-m-d", time()+(86400*30)); ?>">
            </div>
            <div class="col themed-grid-col">
                Order ID:<br>
                <input name="order_id" type="text" class="form-control" placeholder="Order ID of Payment Link">
            </div>
            <div class="col themed-grid-col">
                Amount to be paid:<br>
                <input name="amount" type="number" class="form-control" placeholder="Amount to be paid">
            </div>
            <div class="col themed-grid-col">
                Currency:<br>
                <select name="currency" class="form-control" >
                    <?php
                    foreach($currency->currency_list() as $value) {
                        echo "<option>".$currency->currency($value)[0]."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col themed-grid-col">
                <input type="submit" value="Send Payment Link" name="send_link">
            </div>
        </div>
    </form>
<?php
echo foot();