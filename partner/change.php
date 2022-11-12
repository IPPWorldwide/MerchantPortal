<?php
include("b.php");
echo head();
echo '
<div class="row">
    <div class="col-6">
        <h2>Status</h2>
    </div>
    <div class="col-6 text-end">
        <a class="btn btn-success" href="change.php">'.$lang["PARTNER"]["DASHBOARD"]["CHANGE"].'</a>
    </div>
</div>
<div class="row row-cols-md-3 mb-3">
    <div class="col themed-grid-col chartscol" draggable="true" data-sequence="1">
        <canvas id="chart1" height="230px"></canvas>
        <select data-sequence="1" name="type_1" id="type_1" class="form-control">
            <option value="10m">Last 10 Minutes</option>
            <option value="30m">Last 30 Minutes</option>
        </select>
    </div>
    <div class="col themed-grid-col chartscol" draggable="true" data-sequence="2">
        <canvas id="chart2" height="230px"></canvas>
        <select data-sequence="2" name="type_2" id="type_2" class="form-control">
            <option value="7d">Last 7 Days</option>
            <option value="30d">Last 30 Days</option>
            <option value="90d">Last 90 Days</option>
            <option value="1y">Lastest year</option>
        </select>
    </div>
    <div class="col themed-grid-col chartscol" draggable="true" data-sequence="3">
        <canvas id="chart3" height="230px"></canvas>
        <select data-sequence="3" name="type_3" id="type_3" class="form-control">
            <option value="1y">1 year</option>
            <option value="2y">2 years</option>
            <option value="3y">3 years</option>
        </select>
    </div>
</div>
';
echo foot(); ?>