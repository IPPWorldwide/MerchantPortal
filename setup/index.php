<?php
if(file_exists("../ipp-config-sample.php")) {
    include("../ipp-config-sample.php");
}
$public_page=1;
if(file_exists("../ipp-autoconfig.php") && !isset($_POST["autosetup"])) {
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    include("../ipp-autoconfig.php");
    $create_partner_args = [];
    $create_partner_args["name"]        = $IPP_CONFIG["portal_title"];
    $create_partner_args["email"]       = $IPP_CONFIG["administrator_email"];
    $create_partner_args["password"]    = generateRandomString(16);
    $create_partner_args["url"]         = $actual_link;
    $create_partner_args["cipher"]      = "ABX!lx3a<903234ASDF234WER¤%";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://api.ippeurope.com/create/partner.php");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($create_partner_args));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = json_decode(curl_exec($ch));
    curl_close($ch);
    $IPP_CONFIG["PARTNER_ID"]   = $server_output->content->partner_id;
    $IPP_CONFIG["PARTNER_KEY1"] = $server_output->content->security->key1;
    $IPP_CONFIG["PARTNER_KEY2"] = $server_output->content->security->key2;
    $IPP_CONFIG["portal_url"]   = $actual_link;
    $IPP_CONFIG["autosetup"]    = true;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$actual_link);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($IPP_CONFIG));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    curl_close($ch);
    die();
}
if(isset($_POST["portal_title"])) {
    $folder_level = "./";
    while (!file_exists($folder_level."base.php")) {$folder_level .= "../";}
    define("BASEDIR", $folder_level);
    $myfile = fopen("../ipp-config.php", "w") or die("Unable to open file!");
    fclose($myfile);
    include("../controller/IPPConfig.php");
    $config = new IPPConfig();
    foreach($_POST as $key=>$value) {
        $new_config = $config->UpdateConfig(strtoupper($key),$value);
    }
    $config->WriteConfig();
    include_once "../base.php";
    if(isset($_POST["theme"]) && $_POST["theme"] !== "standard") {
        $theme = $partner->purchaseTheme($_POST["theme"],$_POST["partner_id"],$_POST["partner_key1"]);
        $src = BASEDIR . "theme/" . $_POST["theme"] . "/";
        $filename = $src . basename($theme->{$_POST["theme"]}->file);
        $dirMode = 0755;
        if (!file_exists($src))
            if (!mkdir($src, $dirMode, true) && !is_dir($src)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $src));
            }
        sleep(1);
        file_put_contents($filename, fopen($theme->{$_POST["theme"]}->file, 'r'));
        $zip = new ZipArchive();
        $res = $zip->open($filename);
        if ($res === TRUE) {
            $zip->extractTo($src);
            $zip->close();

        } else {
            throw new \RuntimeException(sprintf('Could not Unzip file at "%s"', $src));
        }
        unlink($filename);
    }
    if(isset($_POST["plugin_email"]) && $_POST["plugin_email"] === "smtp_server") {
        $src = BASEDIR."plugins/".$_POST["plugin_email"]."/";
        $filename = $src . $_POST["plugin_email"].".zip";
        $dirMode = 0755;
        if(!file_exists($src))
            if (!mkdir($src, $dirMode, true) && !is_dir($src)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $src));
            }
        sleep(1);
        file_put_contents($filename, fopen("https://plugins.ippworldwide.com/smtp_server.zip", 'r'));
        $zip = new ZipArchive();
        $res = $zip->open($filename);
        if ($res === TRUE) {
            $zip->extractTo($src);
            $zip->close();
            $partner->InstallPlugin($_POST["plugin_email"],$_POST["partner_id"],$_POST["partner_key1"]);
        } else {
            throw new \RuntimeException(sprintf('Could not Unzip file at "%s"', $src));
        }
        unlink($filename);
    }
    if(isset($_POST["payments_method"]) && $_POST["payments_method"] === "hosted_payment") {
        $src = BASEDIR."plugins/".$_POST["payments_method"]."/";
        $filename = $src . $_POST["payments_method"].".zip";
        $dirMode = 0755;
        if(!file_exists($src))
            if (!mkdir($src, $dirMode, true) && !is_dir($src)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $src));
            }
        sleep(1);
        file_put_contents($filename, fopen("https://plugins.ippworldwide.com/hosted_payment.zip", 'r'));
        $zip = new ZipArchive();
        $res = $zip->open($filename);
        if ($res === TRUE) {
            $zip->extractTo($src);
            $zip->close();
            $partner->InstallPlugin($_POST["payments_method"],$_POST["partner_id"],$_POST["partner_key1"]);
        } else {
            throw new \RuntimeException(sprintf('Could not Unzip file at "%s"', $src));
        }
        unlink($filename);
    }
    die();
}
include("../controller/IPP.php");
include("../controller/Request.php");
include("../controller/IPPCurrency.php");
$currency   = new IPPCurrency();
$request    = new IPPRequest("","");
$ipp        = new IPP($request,null, null);
?>
<?php 
    $basedir = "../";
    if(file_exists($basedir . "ipp-config.php")){
    include $basedir . "ipp-config.php";
?>
    <script>
        confirm("The platform is already setup!");
        window.location = "<?php echo $IPP_CONFIG["PORTAL_URL"]; ?>"
    </script>
<?php die(); } ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="colorlib.com">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Setup IPP Merchant Portal</title>
    <!-- Font Icon -->
    <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="vendor/nouislider/nouislider.min.css">
    <!-- Main css -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="main">
        <div class="container">
            <form method="POST" id="signup-form" class="signup-form" action="#">
                <input type="HIDDEN" name="version" id="version" value="<?php echo $ipp->version()->content->version; ?>" />
                <input type="HIDDEN" name="partner_id" id="partner_id" value="" />
                <input type="HIDDEN" name="partner_key1" id="partner_key1" value="" />
                <input type="HIDDEN" name="partner_key2" id="partner_key2" value="" />
                <div>
                    <h3>Merchant Portal</h3>
                    <fieldset>
                        <h2>Merchant Portal</h2>
                        <p class="desc">You are now seconds away from starting your own Payment Processor.<br />Follow the guide below, and get live.</p>
                        <p class="desc">Now we are defining the details about your Portal where you can administrate your Merchants,<br />and your Merchants can see their transactions.</p>
                        <div class="fieldset-content">
                            <div class="form-row">
                                <div class="form-flex">
                                    <div class="form-group">
                                        <label class="form-label" for="portal_title">Portal Title</label>
                                        <input type="text" name="portal_title" id="portal_title" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-flex">
                                    <div class="form-group">
                                        <label class="form-label" for="administrator_email">Support e-mail</label>
                                        <input type="text" name="administrator_email" id="administrator_email" />
                                        <p class="desc">Define the e-mail used for outbound communication.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-flex">
                                    <div class="form-group form-select-group">
                                        <label class="form-label" for="currency">Standard Currency</label>
                                        <select name="currency" id="currency" class="form-select form-select-lg mb-3">
                                            <?php
                                            foreach($currency->currency_list() as $value) {
                                                echo "<option value='".$value."' ";
                                                if($IPP_CONFIG["CURRENCY"] === $value) { echo "selected"; } echo ">".$currency->currency($value)[0]."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-flex">
                                    <div class="form-group">
                                        <label for="portal_url" class="form-label">Portal URL</label>
                                        <input type="text" name="portal_url" id="portal_url" value="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]".str_replace("/setup","",$_SERVER["REQUEST_URI"]); ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row" style="display: none;">
                                <label class="form-label">API BASE URL</label>
                                <div class="form-flex">
                                    <input type="text" name="global_base_url" id="global_base_url" value="<?php echo $IPP_CONFIG["GLOBAL_BASE_URL"]; ?>" />
                                </div>
                            </div>
                            <div class="form-row" style="display: none;">
                                <label class="form-label">Onboarding BASE URL</label>
                                <div class="form-flex">
                                    <input type="text" name="onboarding_base_url" id="onboarding_base_url" value="<?php echo $IPP_CONFIG["ONBOARDING_BASE_URL"]; ?>" />
                                </div>
                            </div>
                            <div class="form-row" style="display: none;">
                                <label class="form-label">Menu string</label>
                                <div class="form-flex">
                                    <input type="text" name="menu" id="menu" value="<?php echo htmlentities($IPP_CONFIG["MENU"]); ?>" />
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <h3>Theme Setup</h3>
                    <fieldset>
                        <h2>Theme Setup</h2>
                        <p class="desc">Lets make the Portal your own, and ensure the nice look and feel for your Merchants.</p>
                        <div class="fieldset-content">
                            <div class="choose-bank2">
                                <div class="form-radio-flex">
                                    <div class="form-radio-item">
                                        <input type="radio" name="theme" id="standard" value="standard" checked="checked">
                                        <label for="standard"><img src="images/theme_bootstrap.png" alt=""></label>
                                    </div>
                                    <div class="form-radio-item">
                                        <input type="radio" name="theme" id="darkpan" value="darkpan">
                                        <label for="darkpan"><img src="images/theme_darktheme.png" alt=""></label>
                                    </div>
                                    <div class="form-radio-item">
                                        <input type="radio" name="theme" id="niceadmin" value="niceadmin">
                                        <label for="niceadmin"><img src="images/theme_niceadmin.png" alt=""></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                </fieldset>
                    <h3>Payment Flow & Plugins</h3>
                    <fieldset>
                        <h2>Payment Flow & Plugins</h2>
                        <p class="desc">Lets get the standard services set up, once and for all.</p>
                        <h2>Hosted Payment Flow or Injected Payments</h2>
                        <div class="fieldset-content">
                            <div class="choose-bank">
                                <div class="form-radio-flex">
                                    <div class="form-radio-item">
                                        <input type="radio" name="payments_method" id="injected_payment" value="injected_payment" checked="checked">
                                        <label for="injected_payment"><img src="images/payments_injected.png" alt="Payment Page Modal on eCommerce Webshop"></label>
                                        <h3>Injected on eCommerce site</h3>
                                    </div>
                                    <div class="form-radio-item">
                                        <input type="radio" name="payments_method" id="hosted_payment" value="hosted_payment">
                                        <label for="hosted_payment"><img src="images/payments_hosted_flow.png"  alt="Hosted Payment Page. Users get redirected."></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2>Sending out Emails?</h2>
                        <div class="fieldset-content">
                            <div class="choose-bank">
                                <div class="form-radio-flex">
                                    <div class="form-radio-item">
                                        <input type="radio" name="plugin_email" id="smtp_server" value="smtp_server" checked="checked">
                                        <label for="smtp_server"><img src="images/yes.png" alt=""></label>
                                    </div>
                                    <div class="form-radio-item">
                                        <input type="radio" name="plugin_email" id="plugin_email_none" value="none">
                                        <label for="plugin_email_none"><img src="images/no.png" alt=""></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <h3>Create Administrator</h3>
                    <fieldset>
                        <h2>Create Your Administrator</h2>
                        <p class="desc">Create your administrative account below. This account will be set up as a Super-Administrator and can't be deactivated.</p>
                        <div class="fieldset-content">
                            <div class="form-row">
                                <label class="form-label">E-mail</label>
                                <div class="form-flex">
                                    <div class="form-group">
                                        <input type="text" name="partner_email" id="partner_email" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Password</label>
                                <div class="form-flex">
                                    <div class="form-group">
                                        <input type="text" name="partner_password" id="partner_password" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </form>
        </div>

    </div>
    <div class="pyro">
        <div class="title"></div>
        <div class="before"></div>
        <div class="after"></div>
    </div>
    <!-- JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="vendor/jquery-validation/dist/additional-methods.min.js"></script>
    <script src="vendor/jquery-steps/jquery.steps.min.js"></script>
    <script src="vendor/minimalist-picker/dobpicker.js"></script>
    <script src="vendor/nouislider/nouislider.min.js"></script>
    <script src="vendor/wnumb/wNumb.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
