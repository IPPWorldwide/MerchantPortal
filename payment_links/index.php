<?php
include("../base.php");
$merchant_data = $ipp->MerchantData();
$isEcommChannel = false;
foreach($merchant_data->channels as $channels){
    if($channels == 'ecom'){
        $isEcommChannel = true;
    }
}
//$isEcommChannel = false;
if(isset($REQ["send_link"])) {
    $acquirer_data = array();
    $links = $ipp->SendPaymentLink($REQ["sender"],$REQ["recipient"],$REQ["expiry_time"],$REQ["order_id"],$REQ["amount"],$REQ["currency"])->content->sent_links;
    header("Location: ?sent=".$links);
    die();
}
$sent_payment_links = $ipp->ListPaymentLinks();
echo head();
echo "<h2>".$lang["COMPANY"]["PAYMENT_LINKS"]["HEADER"]."</h2>";

if(isset($REQ["sent"]) && $REQ["sent"] >= 1) {
    echo "<div class=\"col md-12 alert-warning rounded text-muted alert\">".$lang["COMPANY"]["PAYMENT_LINKS"]["LINK_SENT"]."</div>";
}
if(isset($REQ["sent"]) && $REQ["sent"] == 0) {
    echo "<div class=\"col md-12 alert-warning rounded text-muted alert\">".$lang["COMPANY"]["PAYMENT_LINKS"]["LINK_NOT_SENT"]."</div>";
}
if($isEcommChannel == true){
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
}else{
    echo '<div class="col alert alert-warning d-flex justify-content-center align-items-center">
        <h5 class="text-dark">'.$lang["COMPANY"]["PAYMENT_LINKS"]["INFO"].'</h5> 
  </div>';
}
echo "<div class='table-responsive'>";
    echo "<table class='table table-striped table-sm'>";
        echo "<thead>";
            echo "<tr>";
                echo "<td>";
                    echo "Link ID";
                echo "</td>";
                echo "<td>";
                    echo "Recipient";
                echo "</td>";
                echo "<td>";
                    echo "Created";
                echo "</td>";
                echo "<td>";
                    echo "Expiry";
                echo "</td>";
                echo "<td>";
                    echo "Amount";
                echo "</td>";
                echo "<td>";
                    echo "Currency";
                echo "</td>";
                echo "<td>";
                    echo "Paid";
                echo "</td>";
            echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach($sent_payment_links as $value) {
            echo "<tr>";
                echo "<td>";
                    echo $value->id;
                echo "</td>";
                echo "<td>";
                    echo $value->recipient;
                echo "</td>";
                echo "<td>";
                    echo $value->dates->created->readable;
                echo "</td>";
                echo "<td>";
                    echo $value->dates->expiry->readable;
                echo "</td>";
                echo "<td>";
                    echo $value->amount->readable;
                echo "</td>";
                echo "<td>";
                    echo $value->currency->text;
                echo "</td>";
                echo "<td>";
                echo "<img src='";
                    if($value->paid)
                        echo "/theme/".$_ENV["THEME"]."/assets/img/yes.png";
                    else
                        echo "/theme/".$_ENV["THEME"]."/assets/img/no.png";
                echo "'>";
            echo "</tr>";
        }
        echo "</tbody>";
    echo "</table>";
echo "</div>";

echo foot();