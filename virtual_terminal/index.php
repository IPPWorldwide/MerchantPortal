<?php
include_once "../base.php";
$merchant_data = $ipp->MerchantData();
if(isset($REQ["start_terminal"])) {
    $gateway    = new IPPGateway($merchant_data->id,$merchant_data->security->key2);
    $data   = [];
    $data["currency"] = $REQ["currency"];
    $data["amount"] = number_format(str_replace(",",".",$REQ["amount"]),2,"","");
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
$actions->get_action("virtual_terminal");
$actions->get_action("theme_replacement");

if(!isset($_POST["start_terminal"])) {
echo '
      <h2>'.$lang["COMPANY"]["VIRTUAL_TERMINAL"]["HEADER"].'</h2>
    <script>
        var payment_settings = {
            "payw_failed_payment"       :   "'.$lang["COMPANY"]["VIRTUAL_TERMINAL"]["FAILED_PAYMENT"].'",
            "payw_cardholder"           :   "'.$lang["COMPANY"]["VIRTUAL_TERMINAL"]["CARDHOLDER"].'",
            "payw_cardno"               :   "'.$lang["COMPANY"]["VIRTUAL_TERMINAL"]["CARDNO"].'",
            "payw_expmonth"             :   "'.$lang["COMPANY"]["VIRTUAL_TERMINAL"]["EXPMONTH"].'",
            "payw_expyear"              :   "'.$lang["COMPANY"]["VIRTUAL_TERMINAL"]["EXPYEAR"].'",
            "payw_cvv"                  :   "'.$lang["COMPANY"]["VIRTUAL_TERMINAL"]["CVV"].'",
            "payw_confirmPayment"       :   "'.$lang["COMPANY"]["VIRTUAL_TERMINAL"]["CONFIRMED"].'",
            "payw_confirmPayment_btn"   :   "'.$lang["COMPANY"]["VIRTUAL_TERMINAL"]["CONFIRMED_BTN"].'",
            "waiting_icon"              :   "https://icon-library.com/images/waiting-icon-png/waiting-icon-png-19.jpg",
        };
    </script>
    <form action="#" method="POST" class="">
        <div class="class="row row-cols-md-3 mb-3">
            <div class="col themed-grid-col">
                '.$lang["COMPANY"]["VIRTUAL_TERMINAL"]["CURRENCY"].'<br>
                <select name="currency" class="form-control" >
                    ';
                        foreach($currency->currency_list() as $value) {
                            echo "<option>".$currency->currency($value)[0]."</option>";
                        }
                    echo '
                </select>
            </div>
            <div class="col themed-grid-col">
                '.$lang["COMPANY"]["VIRTUAL_TERMINAL"]["AMOUNT"].'<br>
                <input name="amount" class="form-control">
            </div>
            <div class="col themed-grid-col">
                '.$lang["COMPANY"]["VIRTUAL_TERMINAL"]["ORDER_ID"].'<br>
                <input name="order_id" class="form-control">
            </div>
            <div class="col themed-grid-col">
                '.$lang["COMPANY"]["VIRTUAL_TERMINAL"]["STORE_CARD"].'<br>
                <input name="rebill" type="checkbox" class="form-check-input" >
            </div>
            <div class="col themed-grid-col">
                '.$lang["COMPANY"]["VIRTUAL_TERMINAL"]["TYPE"].'<br>
                <select name="type" class="form-control">
                    <option>'.$lang["COMPANY"]["VIRTUAL_TERMINAL"]["MOTO"].'</option>
                    <option>'.$lang["COMPANY"]["VIRTUAL_TERMINAL"]["ECOM"].'</option>
                </select>
            </div>
            <div class="col themed-grid-col">
                <input type="submit" value="'.$lang["COMPANY"]["VIRTUAL_TERMINAL"]["START_TERMINAL"].'" name="start_terminal">
            </div>
        </div>
    </form>
';
}
elseif(isset($_POST["start_terminal"])) {
    echo "<script src='https://pay.ippeurope.com/pay.js?checkoutId=".$data_url."&cryptogram=".$cryptogram."'></script><form action='#' class='search-form paymentWidgets' data-brands='VISA MASTER' data-theme='divs'></form>";
}
echo foot();
