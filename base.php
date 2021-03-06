<?php
session_start();
$folder_level = "./";
while (!file_exists($folder_level."base.php")) {$folder_level .= "../";}
define("BASEDIR", $folder_level);
include(BASEDIR . "vendor/autoload.php");

include(BASEDIR . "controller/Request.php");
include(BASEDIR . "controller/IPP.php");
include(BASEDIR . "controller/IPPPayments.php");
include(BASEDIR . "controller/IPPCurrency.php");
include(BASEDIR . "controller/IPPPartner.php");
include(BASEDIR . "controller/IPPPlugins.php");
include(BASEDIR . "controller/IPPMenu.php");
include(BASEDIR . "controller/IPPUtils.php");

if (file_exists(BASEDIR . "ipp-config.php")) {
    include BASEDIR . "ipp-config.php";
} else {
    header("Location: ".BASEDIR."setup");
    die();
}
$_ENV           = $IPP_CONFIG;
$RequestP   = new RequestParams();
$REQ        = $RequestP->getRequestParams($_SERVER["REQUEST_METHOD"]);

$id             = isset($_COOKIE["ipp_user_id"]) ? $_COOKIE["ipp_user_id"] : "";
$session_id     = isset($_COOKIE["ipp_user_session_id"]) ? $_COOKIE["ipp_user_session_id"] : "";
$login_type     = isset($_COOKIE["ipp_type"]) ? $_COOKIE["ipp_type"] : "";

if($id === "" && isset($REQ["user_id"]))
    $id = $REQ["user_id"];
if($session_id === "" && isset($REQ["session_id"]))
    $session_id = $REQ["session_id"];

define("THEMES", BASEDIR . "theme/");
define("THEME", THEMES . $_ENV["THEME"]);
define("ICON_INFO", '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg>');

$request    = new IPPRequest($id,$session_id);
$ipp        = new IPP($request,$id,$session_id);
$partner    = new IPPPartner($request,$id,$session_id);

$plugins    = new IPPPlugins();
$currency   = new IPPCurrency();
$mcc        = new MCC();
$menu       = new IPPMenu();
$utils      = new IPPUtils();
if(isset($_COOKIE["timezone"])) {
    $timezoneoffset = $utils->getTimezoneBasedOnOffsetMinutes($_COOKIE["timezone"]);
    if($timezoneoffset <> "") {
        date_default_timezone_set($timezoneoffset);
    }
}

$langs = $utils->prefered_language(["da","en","da-dk","en-gb"], $_SERVER["HTTP_ACCEPT_LANGUAGE"]);
$language   = $_COOKIE['language'] ?? array_key_first($langs);

$inline_css = [];
$inline_script = [];
$load_script = [];
$load_css = [];
$company_data = new stdClass();
$yes_no_select   = ["No","Yes"];

if(isset($partner_page) && $partner_page == 1) {
    $data = $partner->checkLogin();
    if(!$data->success) {
        header("Location: /");
        die();
    }
    $user_data = $partner->UserData();
    require_once(THEME."/functions.php");
    require_once(THEME."/partner/head.php");
    require_once(THEME."/partner/foot.php");
}
elseif(!isset($public_page) || (isset($public_page) && !$public_page)) {
    $company_data = $ipp->checkLogin();
    if(!$company_data->success) {
        header("Location: /");
        die();
    }
    require_once("theme/".$_ENV["THEME"]."/functions.php");
    require_once("theme/".$_ENV["THEME"]."/head.php");
    require_once("theme/".$_ENV["THEME"]."/foot.php");
}
$plugins->loadPlugins();

require_once(BASEDIR . "language/en-gb.php");
if(file_exists(THEME . "/language/$language.php"))
    require_once(THEME . "/language/$language.php");

