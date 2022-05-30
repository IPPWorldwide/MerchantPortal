<?php
include("../ipp-config-sample.php");
    if(isset($_POST["portal_title"])) {
        $myfile = fopen("../ipp-config.php", "w") or die("Unable to open file!");
        fclose($myfile);
        define("BASEDIR", "../");
        include(BASEDIR . "controller/IPPConfig.php");
        $config = new IPPConfig();

        foreach($_POST as $key=>$value) {
            $new_config = $config->UpdateConfig(strtoupper($key),$value);
        }
        $config->WriteConfig();
        die();
    }
?>
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
                <div>
                    <h3>Merchant Portal</h3>
                    <fieldset>
                        <h2>Merchant Portal</h2>
                        <p class="desc">You are now seconds away from starting your own Payment Processor.<br />Follow the guide below, and get live.</p>
                        <div class="fieldset-content">
                            <div class="form-row">
                                <label class="form-label">Portal Title</label>
                                <div class="form-flex">
                                    <div class="form-group">
                                        <input type="text" name="portal_title" id="portal_title" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Administrative e-mail</label>
                                <div class="form-flex">
                                    <div class="form-group">
                                        <input type="text" name="administrator_email" id="administrator_email" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Standard Currency</label>
                                <div class="form-flex">
                                    <div class="form-group">
                                        <input type="text" name="currency" id="currency" value="<?php echo $IPP_CONFIG["CURRENCY"]; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Portal URL</label>
                                <div class="form-flex">
                                    <input type="text" name="portal_url" id="portal_url" value="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]".str_replace("/setup","",$_SERVER["REQUEST_URI"]); ?>" />
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
                                <label class="form-label">IPP Partner ID</label>
                                <div class="form-flex">
                                    <div class="form-group">
                                        <input type="text" name="partner_id" id="partner_id" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Partner Key 1</label>
                                <div class="form-flex">
                                    <div class="form-group">
                                        <input type="text" name="partner_key1" id="partner_key1" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Partner Key 2</label>
                                <div class="form-flex">
                                    <div class="form-group">
                                        <input type="text" name="partner_key2" id="partner_key2" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Theme used</label>
                                <div class="form-flex">
                                    <div class="form-group">
                                        <input type="text" name="theme" id="theme" value="standard" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Version</label>
                                <div class="form-flex">
                                    <div class="form-group">
                                        <input type="text" name="version" id="version" value="1.0.0" />
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