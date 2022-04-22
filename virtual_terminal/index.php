<?php
include("../base.php");
$merchant_data = $ipp->MerchantData();
if(isset($REQ["start_terminal"])) {
    $gateway    = new IPPGateway($merchant_data->id,$merchant_data->security->key2);
    $data   = [];
    $data["currency"] = $REQ["currency"];
    $data["amount"] = number_format($REQ["amount"],2,"","");
    $data["order_id"] = $REQ["order_id"];
    if(isset($REQ["rebill"]))
        $data["rebill"] = $REQ["rebill"];
    $data["transaction_type"] = $REQ["type"];
    $data["ipn"] = "https://www.google.dk";

    $data = $gateway->checkout_id($data);


    $data_url = $data->checkout_id;
    $cryptogram = $data->cryptogram;
    $action = "success.php";
}

echo head();
if(!isset($_POST["start_terminal"])) {
?>
      <h2>Virtual Terminal</h2>
    <script>
        var payment_settings = {
            "payw_failed_payment"       :   "Betalingen fejlede, forsøg venligst igen.",
            "payw_cardholder"           :   "Kortholder",
            "payw_cardno"               :   "Kortnummer",
            "payw_expmonth"             :   "Udløbsmåned",
            "payw_expyear"              :   "Udløbsår",
            "payw_cvv"                  :   "CVV",
            "payw_confirmPayment"       :   "Knap",
            "payw_confirmPayment_btn"   :   "Gennemfør",
            "waiting_icon"              :   "https://icon-library.com/images/waiting-icon-png/waiting-icon-png-19.jpg",
        };
    </script>
    <form action="#" method="POST" class="">
        <div class="class="row row-cols-md-3 mb-3">
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
                Amount:<br>
                <input name="amount" class="form-control">
            </div>
            <div class="col themed-grid-col">
                Order ID:<br>
                <input name="order_id" class="form-control">
            </div>
            <div class="col themed-grid-col">
                Store Card for later use:<br>
                <input name="rebill" type="checkbox" class="form-check-input" >
            </div>
            <div class="col themed-grid-col">
                Type:<br>
                <select name="type" class="form-control">
                    <option>MOTO</option>
                    <option>ECOM</option>
                </select>
            </div>
            <div class="col themed-grid-col">
                <input type="submit" value="Start Virtual Terminal" name="start_terminal">
            </div>
        </div>
    </form>
<?php
}
elseif(isset($_POST["start_terminal"])) {
    echo "<script src='https://pay.ippeurope.com/pay.js?checkoutId=".$data_url."&cryptogram=".$cryptogram."'></script><form action='#' class='search-form paymentWidgets' data-brands='VISA MASTER' data-theme='divs'></form>";
}

echo foot();