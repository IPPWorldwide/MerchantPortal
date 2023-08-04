<?php
include("../b.php");
if(isset($REQ) && count($REQ) > 0) {
    $partner_data = $partner->PartnerData();
    $shopping_carts = [];
    foreach($REQ as $key=>$value) {
        $shopping_carts[$key] = $value;
        if(!file_exists(PUBLIC_FILES.$key.".zip")) {
            $ch = curl_init();
            $source = "https://github.com/IPPWorldwide/Sample-$key/archive/refs/heads/main.zip"; //$source = $dynamic_url
            curl_setopt($ch, CURLOPT_URL, $source);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: application/vnd.github+json',
                'X-GitHub-Api-Version: 2022-11-28',
                'User-Agent: IPP'
            ]);
            $data = curl_exec ($ch);
            curl_close ($ch);
            $destination = PUBLIC_FILES.$key.".zip";
            $file = fopen($destination, "w+");
            fputs($file, $data);
            fclose($file);
            $zip = new ZipArchive;
            $res = $zip->open($destination);
            if ($res === TRUE) {
                $zip->extractTo(PUBLIC_FILES.'/temp_shopping_cart/');
                $zip->close();
                unlink($destination);
                $temp_folder_name = PUBLIC_FILES."/temp_shopping_cart/Sample-$key-main/";
                $partner_name = $partner_data->meta_data->name;
                $partner_url = $IPP_CONFIG["PORTAL_URL"];
                $cart_partner_name = strtolower(str_replace(" ","_",$partner_name));
                $cart_file_handling_name = $temp_folder_name . $cart_partner_name."/";
                include(PUBLIC_FILES."/temp_shopping_cart/Sample-$key-main/import.php");
                $zipArchive = new ZipArchive();
                if ($zipArchive->open($destination, ZipArchive::CREATE) !== TRUE) {
                    exit("Unable to open file.");
                }
                $utils->createZip($zipArchive, $cart_file_handling_name, $cart_partner_name);
                $fp = fopen('config.php', 'w');//opens file in write-only mode
                fwrite($fp, '<?php ');
                fwrite($fp, '$configuration["name"] = "'.$partner_name.'"; ');
                fwrite($fp, '$configuration["url"] = "'.$IPP_CONFIG["GLOBAL_BASE_URL"].'"; ');
                fwrite($fp, '$configuration["onboarding"] = "'.$IPP_CONFIG["ONBOARDING_BASE_URL"].'"; ');
                fwrite($fp, '$configuration["portal"] = "'.$IPP_CONFIG["PORTAL_URL"].'"; ');
                fwrite($fp, '?>');
                fclose($fp);
                $zipArchive->addFile("config.php",$cart_partner_name . "/config.php");
                $zipArchive->close();
                unlink("config.php");

                $utils->recurseRmdir(PUBLIC_FILES.'/temp_shopping_cart/');
            }
        }
    }
    $config = new IPPConfig();
    $new_config = $config->UpdateConfig("ENABLED_SHOPPING_CARTS",json_encode($shopping_carts));
    $config = $config->WriteConfig();
}
if(isset($IPP_CONFIG["ENABLED_SHOPPING_CARTS"]))
    $current_carts = json_decode($IPP_CONFIG["ENABLED_SHOPPING_CARTS"], true);
else
    $current_carts = [];
echo head();
$actions->get_action("external_platforms");

//$supported_carts = ["woocommerce","magento","prestashop","opencart"];
$supported_carts = ["woocommerce"];
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
