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
echo "<h2>".$lang["COMPANY"]["PAYMENT_LINKS"]["HEADER"]."</h2>";

if(isset($REQ["sent"]) && $REQ["sent"] == 1) {
    echo "<div class=\"col md-12 alert-warning rounded text-muted alert\">".$lang["COMPANY"]["PAYMENT_LINKS"]["LINK_SENT"]."</div>";
}
echo '
    <form action="?" method="POST">
        <div class="class="row row-cols-md-3 mb-3">
            <div class="col themed-grid-col">
                '.$lang["COMPANY"]["PAYMENT_LINKS"]["SENDER"].'<br>
                <input name="sender" class="form-control" placeholder="'.$lang["COMPANY"]["PAYMENT_LINKS"]["PLACEHOLDER_SENDER"].'">
            </div>
            <div class="col themed-grid-col">
                '.$lang["COMPANY"]["PAYMENT_LINKS"]["RECIPIENT"].'<br>
                <input name="recipient" class="form-control" placeholder="'.$lang["COMPANY"]["PAYMENT_LINKS"]["PLACEHOLDER_RECIPIENT"].'">
            </div>
            <div class="col themed-grid-col">
                '.$lang["COMPANY"]["PAYMENT_LINKS"]["EXPIRY"].'<br>
                <input type="date" name="expiry_time" class="form-control" value="'.date("Y-m-d", time()+(86400*30)).'">
            </div>
            <div class="col themed-grid-col">
                '.$lang["COMPANY"]["PAYMENT_LINKS"]["ORDER_ID"].'<br>
                <input name="order_id" type="text" class="form-control" placeholder="'.$lang["COMPANY"]["PAYMENT_LINKS"]["PLACEHOLDER_ORDER_ID"].'">
            </div>
            <div class="col themed-grid-col">
                '.$lang["COMPANY"]["PAYMENT_LINKS"]["AMOUNT_PAID"].'<br>
                <input name="amount" type="number" class="form-control" placeholder="'.$lang["COMPANY"]["PAYMENT_LINKS"]["PLACEHOLDER_AMOUNT_PAID"].'">
            </div>
            <div class="col themed-grid-col">
                '.$lang["COMPANY"]["PAYMENT_LINKS"]["CURRENCY"].'<br>
                <select name="currency" class="form-control" >
                    ';
                    foreach($currency->currency_list() as $value) {
                        echo "<option>".$currency->currency($value)[0]."</option>";
                    }
                    echo '
                </select>
            </div>
            <div class="col themed-grid-col">
                <input type="submit" value="'.$lang["COMPANY"]["PAYMENT_LINKS"]["SUBMIT"].'" name="send_link">
            </div>
        </div>
    </form>
';
echo foot();