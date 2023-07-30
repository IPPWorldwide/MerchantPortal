<?php
include("../b.php");
if(isset($REQ) && count($REQ) > 0) {
    $shopping_carts = [];
    foreach($REQ as $key=>$value)
        $shopping_carts[$key] = $value;
    $config = new IPPConfig();
    $new_config = $config->UpdateConfig("ENABLED_SHOPPING_CARTS",json_encode($shopping_carts));
    $config = $config->WriteConfig();
}
$current_carts = json_decode($IPP_CONFIG["ENABLED_SHOPPING_CARTS"], true) ?? [];
echo head();
$actions->get_action("external_platforms");

$supported_carts = ["woocommerce","magento","prestashop","opencart"];
echo '
        <form action="?" method="POST" class="form">
            <h2>'.$lang["PARTNER"]["SHOPPING_CARTS"]["HEADER"].'</h2>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">
                    <div class="row row-cols-md-3 mb-3">
                        <div class="col themed-grid-col">
                            ';
                            foreach($supported_carts as $value) {
                                echo '
                            <div class="form-check">
                                <label class="form-check-label" for="'.strtolower($value).'">
                                  '.ucfirst($value).'
                                </label>
                                <input name="'.strtolower($value).'" class="form-check-input" type="checkbox" id="'.strtolower($value).'"';
                                if(array_key_exists(strtolower($value), $current_carts))
                                    echo " checked";
                                echo '>
                            </div>
                            ';
                            }
                            echo '
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row-cols-md-1 mb-1">
                <div class="d-flex">
                    <button type="submit" class="btn btn-primary mb-3">'.$lang["PARTNER"]["DATA"]["SAVE"].'</button>
                </div>
            </div>
        </form>
';
echo foot();
