<?php
include_once("../base.php");
$merchant_data = $ipp->MerchantData();
echo head();

echo "<div style='height: 100%;width:100%;'><center><h2>Confirmed Payment</h2><br /><img src='/theme/".$_ENV["THEME"]."/assets/img/128/check_sign_icon.png'></center></div>";
echo foot();
