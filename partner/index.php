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
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
          '.$lang["PARTNER"]["DASHBOARD"]["CHANGE"].'
        </button>
        </div>
<div class="row row-cols-md-3 mb-3">
    <div class="col themed-grid-col chartscol" data-sequence="1">
        <canvas id="chart1" height="230px"></canvas>
        <select data-sequence="1" name="type_1" id="type_1" data-updateframe="30000" class="form-control">
            <option value="10m">Last 10 Minutes</option>
            <option value="30m">Last 30 Minutes</option>
        </select>
    </div>
    <div class="col themed-grid-col chartscol" data-sequence="2">
        <canvas id="chart2" height="230px"></canvas>
        <select data-sequence="2" name="type_2" id="type_2" data-updateframe="360000" class="form-control">
            <option value="7d">Last 7 Days</option>
            <option value="30d">Last 30 Days</option>
            <option value="90d">Last 90 Days</option>
            <option value="1y">Lastest year</option>
        </select>
    </div>
    <div class="col themed-grid-col chartscol" data-sequence="3">
        <canvas id="chart3" height="230px"></canvas>
        <select data-sequence="3" name="type_3" id="type_3" data-updateframe="480000" class="form-control">
            <option value="1y">1 year</option>
            <option value="2y">2 years</option>
            <option value="3y">3 years</option>
        </select>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Understood</button>
      </div>
    </div>
  </div>
</div>

';
echo foot(); ?>
