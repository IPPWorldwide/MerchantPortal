<?php
include("b.php");
if(isset($REQ["update"]) && $REQ["update"] == "true"):
    header( "Location: /update.php?version=".$ipp->version()->content->version);
    die();
endif;
if(!isset($IPP_CONFIG["INTERACTIVE_GUIDE"])) {
    if(class_exists('ZipArchive')) {
        $src = BASEDIR."plugins/interactive_guide/";
        $filename = $src . basename("https://plugins.ippworldwide.com/interactive_guide.zip");
        $dirMode = 0755;
        if(!file_exists($src))
            if (!mkdir($src, $dirMode, true) && !is_dir($src)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $src));
            }
        sleep(1);
        file_put_contents($filename, fopen("https://plugins.ippworldwide.com/interactive_guide.zip", 'r'));
        $zip = new ZipArchive();
        $res = $zip->open($filename);
        if ($res === TRUE) {
            $zip->extractTo($src);
            $zip->close();
            $partner->InstallPlugin("interactive_guide");
        } else {
            throw new \RuntimeException(sprintf('Could not Unzip file at "%s"', $src));
        }
        unlink($filename);
    }
    include(BASEDIR . "controller/IPPConfig.php");
    $config = new IPPConfig();
    $config->UpdateConfig("INTERACTIVE_GUIDE","1");
    $config = $config->WriteConfig();
}
echo head();
$actions->get_action("partner_dashboard");

if($_ENV["VERSION"] < $ipp->version()->content->version):
    ?>
    <div class="alert alert-warning" role="alert"><?=$lang["PARTNER"]["DASHBOARD"]["OUTDATED_VERSION"]?><a href='?update=true'><?=$lang["PARTNER"]["DASHBOARD"]["UPDATE_HERE"]?></a></div>
    <?php foreach($ipp->ListVersions() as $key=>$value):
    if($_ENV["VERSION"] < $key):
        echo "<div class='alert alert-warning' role='alert'><h3>$key - $value</h3><p>";
        $pageDocument = @file_get_contents("https://raw.githubusercontent.com/IPPWorldwide/MerchantPortal/".$key."/CHANGES.md");
        if ($pageDocument !== false):
            echo nl2br($pageDocument);
        endif;
        echo "</p></div>";
    endif;
endforeach;
endif;
echo '
    <div class="row">
        <div class="col-6">
            <h2>Status</h2>
        </div>
        <div class="col-6 text-end">
        <button type="button" class="btn btn-success btnChangeDashboard">
          '.$lang["PARTNER"]["DASHBOARD"]["CHANGE"].'
        </button>
    </div>
    <div class="row AddNewElementToPage">
        <div class="col-2">
            Add new Element
        </div>
        <div class="col-3">
            <select type="select" class="form-select ElementType" name="ElementType">
                <option value="0">-- CHOOSE TYPE --</option>
                <option value="GraphBar">Graph, Bar</option>
                <option value="GraphLine">Graph, Line</option>
                <option value="Number">Number</option>
            </select>
        </div>
        <div class="col-3">
            <div class="form-group row">
                <select type="select" class="form-control ElementContent selectpicker" name="ElementContent" data-live-search="true">
                    <option value="0">-- CHOOSE DATA --</option>            
                    <option data-tokens="customers_created_7_days" value="customers_created_7_days">-- Created Customers, past 7 days --</option>            
                </select>
            </div>
        </div>
        <div class="col-4">
            <button type="button" class="btn btn-success btnAddElement" disabled="disabled">
              '.$lang["PARTNER"]["DASHBOARD"]["ADD_ELEMENT"].'
            </button>
        </div>
    </div>
    <div class="row row-cols-md-3 mb-3">
    <div class="col themed-grid-col chartscol" data-sequence="1">
        <div class="content">
            <canvas id="chart1" height="230px"></canvas>
            <select data-sequence="1" name="type_1" id="type_1" data-updateframe="30000" class="form-control">
                <option value="10m">Last 10 Minutes</option>
                <option value="30m">Last 30 Minutes</option>
            </select>
        </div>
        <div class="settings">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
              '.$lang["PARTNER"]["DASHBOARD"]["CHANGE_ELEMENT"].'
            </button>
        </div>
    </div>
    <div class="col themed-grid-col chartscol" data-sequence="2">
        <div class="content">
            <canvas id="chart2" height="230px"></canvas>
            <select data-sequence="2" name="type_2" id="type_2" data-updateframe="360000" class="form-control">
                <option value="7d">Last 7 Days</option>
                <option value="30d">Last 30 Days</option>
                <option value="90d">Last 90 Days</option>
                <option value="1y">Lastest year</option>
            </select>
        </div>
        <div class="settings">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
              '.$lang["PARTNER"]["DASHBOARD"]["CHANGE_ELEMENT"].'
            </button>
        </div>
    </div>
    <div class="col themed-grid-col chartscol" data-sequence="3">
        <div class="content">
            <canvas id="chart3" height="230px"></canvas>
            <select data-sequence="3" name="type_3" id="type_3" data-updateframe="480000" class="form-control">
                <option value="1y">1 year</option>
                <option value="2y">2 years</option>
                <option value="3y">3 years</option>
            </select>
        </div>
        <div class="settings">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
              '.$lang["PARTNER"]["DASHBOARD"]["CHANGE_ELEMENT"].'
            </button>
        </div>
    </div>
</div>
';
$load_script[] = "https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js";
$load_css[] = "https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css";


echo foot(); ?>
