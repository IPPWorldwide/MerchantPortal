<?php
include("b.php");
if(isset($REQ["update"]) && $REQ["update"] == "true"):
    header( "Location: /update.php?version=".$ipp->version()->content->version);
    die();
endif;
if(!isset($IPP_CONFIG["INTERACTIVE_GUIDE"])) {
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
    include(BASEDIR . "controller/IPPConfig.php");
    $config = new IPPConfig();
    $config->UpdateConfig("INTERACTIVE_GUIDE","1");
    $config = $config->WriteConfig();
}
echo head();

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
            <a class="btn btn-success" href="change.php">'.$lang["PARTNER"]["DASHBOARD"]["CHANGE"].'</a>
        </div>
<div class="row row-cols-md-3 mb-3">
    <div class="col themed-grid-col chartscol" data-sequence="1">
        <canvas id="chart1" height="230px"></canvas>
        <select data-sequence="1" name="type_1" id="type_1" class="form-control">
            <option value="10m">Last 10 Minutes</option>
            <option value="30m">Last 30 Minutes</option>
        </select>
    </div>
    <div class="col themed-grid-col chartscol" data-sequence="2">
        <canvas id="chart2" height="230px"></canvas>
        <select data-sequence="2" name="type_2" id="type_2" class="form-control">
            <option value="7d">Last 7 Days</option>
            <option value="30d">Last 30 Days</option>
            <option value="90d">Last 90 Days</option>
            <option value="1y">Lastest year</option>
        </select>
    </div>
    <div class="col themed-grid-col chartscol" data-sequence="3">
        <canvas id="chart3" height="230px"></canvas>
        <select data-sequence="3" name="type_3" id="type_3" class="form-control">
            <option value="1y">1 year</option>
            <option value="2y">2 years</option>
            <option value="3y">3 years</option>
        </select>
    </div>
</div>
<div class="chart-container">
    <!--    <div class="card" data-sequence="2">
            <div class="card-header">Daily Graph</div>
            <div class="card-body">

            </div>
        </div>
        <div class="card" data-sequence="3">
            <div class="card-header">Monthly Graph</div>
            <div class="card-body">

            </div>
        </div>
        <div class="card" data-sequence="4">
            <div class="card-header">Percentage Increase/Decrease in Transactions</div>
            <div class="card-body">
                <canvas id="chart4" width="600px" height="230px"></canvas>
                <select data-sequence="4" name="type_4" id="type_4" class="form-control">
                    <option>This Month</option>
                    <option>Last Month</option>
                </select>
            </div>
        </div>
        <div class="card" data-sequence="5">
            <div class="card-header"> Percentage Increase/Decrease in Transactions</div>
            <div class="card-body">
                <canvas id="chart5" width="600px" height="230px"></canvas>
                <select data-sequence="5" name="type_5" id="type_5" class="form-control">
                    <option>This Week</option>
                    <option>This Month</option>
                    <option value="today">Today</option>
                    <option value="7">Last 7 Days</option>
                    <option value="15">Last 15 Days</option>
                    <option value="28">Last 28 Days</option>
                </select>
            </div>
        </div>
    </div> -->
';
echo foot(); ?>