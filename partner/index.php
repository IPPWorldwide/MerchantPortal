
<?php
include("b.php");
if(isset($REQ["update"]) && $REQ["update"] == "true"):
    header( "Location: /update.php?version=".$ipp->version()->content->version);
    die();
endif;
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
?>

<div class="chart-container">
    <div draggable="true" class="card" data-sequence="1">
        <div class="card-header">Payments in Last 30 minutes</div>
        <div class="card-body">
            <canvas id="chart1" width="600px" height="230px"></canvas>
            <select data-sequence="1" name="type_1" id="type_1" class="form-control">
                <option value="7">Last 30 Minutes</option>
                <option value="15">Last 24 Hours</option>
            </select>
        </div>
    </div>
    <div draggable="true" class="card" data-sequence="2">
        <div class="card-header">The Payments Graph</div>
        <div class="card-body">
            <canvas id="chart2" width="600px" height="230px"></canvas>
            <select data-sequence="2" name="type_2" id="type_2" class="form-control">
                <option value="7">Last 7 Days</option>
                <option value="15">Last 15 Days</option>
                <option value="28">Last 28 Days</option>
            </select>
        </div>
    </div>
    <div draggable="true" class="card" data-sequence="3">
        <div class="card-header">Chart 3</div>
        <div class="card-body">
            <canvas id="chart3" width="600px" height="230px"></canvas>
            <!-- <select data-sequence="3" name="type_3" id="type_3" class="form-control">
                <option value="today">Today</option>
                <option value="7">Last 7 Days</option>
                <option value="15">Last 15 Days</option>
                <option value="28">Last 28 Days</option>
            </select> -->
        </div>
    </div>
    <div draggable="true" class="card" data-sequence="4">
        <div class="card-header">Percentage Increase/Decrease in Transactions</div>
        <div class="card-body">
            <canvas id="chart4" width="600px" height="230px"></canvas>
            <!-- <select data-sequence="4" name="type_4" id="type_4" class="form-control">
                <option>This Month</option>
                <option>Last Month</option>
            </select> -->
        </div>
    </div>
    <div draggable="true" class="card" data-sequence="5">
        <div class="card-header"> Percentage Increase/Decrease in Transactions</div>
        <div class="card-body">
            <canvas id="chart5" width="600px" height="230px"></canvas>
            <!-- <select data-sequence="5" name="type_5" id="type_5" class="form-control">
                <option>This Week</option>
                <option>This Month</option>
                <option value="today">Today</option>
                <option value="7">Last 7 Days</option>
                <option value="15">Last 15 Days</option>
                <option value="28">Last 28 Days</option>
            </select> -->
        </div>
    </div>
</div>

<?php echo foot(); ?>