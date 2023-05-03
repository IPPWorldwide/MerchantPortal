<?php
include("b.php");
$config     = new IPPConfig();

if(isset($REQ["update"]) && $REQ["update"] == "true") {
    header( "Location: /update.php?version=".$ipp->version()->content->version);
    die();
}
if(isset($REQ["action"]) && $REQ["action"] === "addElement") {
    $current = $config->ReadConfig("admin_user_".$id."_dashboard");
    $current = json_decode($current, true);
    $current[][$REQ["data"]] = $REQ["type"];
    $config->UpdateConfig("admin_user_".$id."_dashboard",json_encode($current));
    $config = $config->WriteConfig();
    echo $partner_graph->GenerateHTML(($REQ["total"]+1),$partner_graph->getDataSource($REQ["data"])["title"],$REQ["data"],$REQ["type"]);
    die();
}
if(isset($REQ["action"]) && $REQ["action"] === "removeElement") {
    $current = $config->ReadConfig("admin_user_".$id."_dashboard");
    $current = json_decode($current, true);
    array_splice($current, ($REQ["sequence"]-1), 1);
    $config->UpdateConfig("admin_user_".$id."_dashboard",json_encode($current));
    $config = $config->WriteConfig();
    die();
}
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
$elements = json_decode($config->ReadConfig("admin_user_".$id."_dashboard"), JSON_THROW_ON_ERROR,512);
$available_elements = $partner_graph->data_sources;
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
            <div class="form-group row">
                <select type="select" class="form-control ElementContent selectpicker" name="ElementContent" data-live-search="true">
                    <option value="0">-- CHOOSE DATA --</option>           
                    ';
                    foreach($available_elements as $value) {
                        echo '<option data-tokens="'.$value["id"].'" value="'.$value["id"].'">'.$value["title"].'</option>';
                    }
                    echo '
                </select>
            </div>
        </div>
        <div class="col-3">
            <select type="select" class="form-select ElementType" name="ElementType">
                <option value="0">-- CHOOSE TYPE --</option>
                <option value="GraphBar">Graph, Bar</option>
                <option value="GraphLine">Graph, Line</option>
                <option value="Number">Number</option>
            </select>
        </div>
        <div class="col-4">
            <button type="button" class="btn btn-success btnAddElement" disabled="disabled">
              '.$lang["PARTNER"]["DASHBOARD"]["ADD_ELEMENT"].'
            </button>
        </div>
        <div class="col-12">&nbsp;</div>
    </div>
    <div class="row row-cols-md-3 mb-3 DashboardElements">
    ';
    $i=1;
    if(isset($elements) && is_array((array)$elements) && count((array)$elements)>1) {
        foreach($elements as $element) {
            echo $partner_graph->GenerateHTML($i,$partner_graph->getDataSource(key($element))["title"],key($element),$element[key($element)]);
            $i++;
        }
    } else {
        echo $partner_graph->GenerateHTML(1,$partner_graph->getDataSource("customers_created_7_days")["title"],"customers_created_7_days","GraphLine");
        echo $partner_graph->GenerateHTML(2,$partner_graph->getDataSource("transactions_approved_7_days")["title"],"transactions_approved_7_days","GraphLine");
        echo $partner_graph->GenerateHTML(3,$partner_graph->getDataSource("transactions_approved_30_days")["title"],"transactions_approved_30_days","GraphLine");
    }
    echo '
</div>
';
$load_script[] = "https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js";
$load_css[] = "https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css";


echo foot(); ?>
