<?php

$folder_level = "./";
while (!file_exists($folder_level."base.php")) {$folder_level .= "../";}
define("BASEDIR", $folder_level);
include(BASEDIR . "vendor/autoload.php");

include(BASEDIR . "controller/Request.php");
include(BASEDIR . "controller/IPP.php");
include(BASEDIR . "controller/IPPPayments.php");
include(BASEDIR . "controller/IPPCurrency.php");
include(BASEDIR . "controller/IPPPartner.php");


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$id             = isset($_COOKIE["ipp_id"]) ? $_COOKIE["ipp_id"] : "";
$session_id     = isset($_COOKIE["ipp_session_id"]) ? $_COOKIE["ipp_session_id"] : "";
$login_type     = isset($_COOKIE["ipp_type"]) ? $_COOKIE["ipp_type"] : "";

$request    = new IPPRequest();
$ipp        = new IPP($request,$id,$session_id);
$partner    = new IPPPartner($request,$id,$session_id);
$currency   = new IPPCurrency($request);
$RequestP   = new RequestParams($request);
$mcc        = new MCC();

$REQ        = $RequestP->getRequestParams($_SERVER["REQUEST_METHOD"]);

$inline_css = [];
$inline_script = [];
$load_script = [];

if(isset($partner_page) && $partner_page == 1) {
    $data = $partner->checkLogin();
    if(!$data->success) {
        header("Location: /");
        die();
    }
}
elseif(!isset($public_page) || (isset($public_page) && !$public_page)) {
    $data = $ipp->checkLogin();
    if(!$data->success) {
        header("Location: /");
        die();
    }
    require_once("theme/head.php");
    require_once("theme/foot.php");
}