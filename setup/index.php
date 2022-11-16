<?php
include("../ipp-config-sample.php");
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
                <input type="HIDDEN" name="theme" id="theme" value="standard" />
                <input type="HIDDEN" name="version" id="version" value="<?php echo $ipp->version()->content->version; ?>" />
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
                                        <label class="form-label" for="administrator_email">Administrative e-mail</label>
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
                                    <input type="text" name="global_base_url" id="global_base_url" value="https://api.ippeurope.com" />
                                </div>
                            </div>
                            <div class="form-row" style="display: none;">
                                <label class="form-label">Onboarding BASE URL</label>
                                <div class="form-flex">
                                    <input type="text" name="onboarding_base_url" id="onboarding_base_url" value="https://onboarding.api.ippeurope.com" />
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
                    <h3>Connectivity Details</h3>
                    <fieldset>
                        <h2>Connectivity Details</h2>
                        <p class="desc">These details have been provided by IPP. Reach out to your representative if you aren't sure.</p>
                        <div class="fieldset-content">
                            <div class="form-row">
                                <label class="form-label">ID</label>
                                <div class="form-flex">
                                    <div class="form-group">
                                        <input type="text" name="partner_id" id="partner_id" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Key 1</label>
                                <div class="form-flex">
                                    <div class="form-group">
                                        <input type="text" name="partner_key1" id="partner_key1" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Key 2</label>
                                <div class="form-flex">
                                    <div class="form-group">
                                        <input type="text" name="partner_key2" id="partner_key2" />
                                    </div>
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
                                        <input type="radio" name="selected_theme" id="standard" value="standard" checked="checked">
                                        <label for="standard"><img src="images/theme_bootstrap.png" alt=""></label>
                                    </div>
                                    <div class="form-radio-item">
                                        <input type="radio" name="selected_theme" id="theme_two" value="theme_two">
                                        <label for="theme_two"><img src="images/bank-2.jpg" alt=""></label>
                                    </div>
                                    <div class="form-radio-item">
                                        <input type="radio" name="selected_theme" id="theme_three" value="theme_three">
                                        <label for="theme_three"><img src="images/bank-3.jpg" alt=""></label>
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
                                        <label for="injected_payment"><img src="images/payments_injected.png" alt=""></label>
                                        <h3>Injected on eCommerce site</h3>
                                    </div>
                                    <div class="form-radio-item">
                                        <input type="radio" name="payments_method" id="hosted_flow" value="hosted_flow">
                                        <label for="hosted_flow"><img src="images/payments_hosted_flow.png" alt=""></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2>Sending out Emails?</h2>
                        <div class="fieldset-content">
                            <div class="choose-bank">
                                <div class="form-radio-flex">
                                    <div class="form-radio-item">
                                        <input type="radio" name="plugin_email" id="plugin_email_none" value="none" checked="checked">
                                        <label for="plugin_email_none"><img src="images/disabled_stop.png" alt=""></label>
                                    </div>
                                    <div class="form-radio-item">
                                        <input type="radio" name="plugin_email" id="smtp" value="smtp">
                                        <label for="smtp"><img src="images/email.png" alt=""></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </form>
        </div>

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
