<?php
include_once("../base.php");
$merchant_data = $ipp->MerchantData();
echo head();
$actions->get_action("virtual_terminal_success");
$actions->get_action("theme_replacement");

echo "<div style='height: 100%;width:100%;'><center><h2>Confirmed Payment</h2><br /><img src='/theme/".$_ENV["THEME"]."/assets/img/128/check_sign_icon.png'></center></div>";
echo foot();
