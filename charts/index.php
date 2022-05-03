<?php
include("../base.php");

echo head();
?>
      <h2>Charts</h2>
        <div class="card chart-container">
            <canvas id="chart_tnx"></canvas>
        </div>
        <div class="card chart-container">
            <canvas id="chart_amount"></canvas>
        </div>
    </main>
  </div>
</div>
<?php
$inline_css = ["
    .chart-container {
        width: 50%;
        height: 50%;
        margin: auto;
    }"];

$charts = $ipp->Charts();
$ch_count = count((array)$charts);
$i = 1;
$tnx_labels = "";
foreach ($charts as $value) {
    $tnx_labels .= "\"" . $value->date . "\"";
    if ($ch_count != $i)
        $tnx_labels .= ",";
    $i++;
}
$charts = $ipp->Charts();
$ch_count = count((array)$charts);
$i = 1;
$tnx_data_set = "";
foreach ($charts as $value) {
    $tnx_data_set .= $value->count;
    if ($ch_count != $i)
        $tnx_data_set .= ",";
    $i++;
}

$charts = $ipp->Charts();
$ch_count = count((array)$charts);
$i = 1;
$amount_label = "";
foreach ($charts as $value) {
    $amount_label .= "\"" . $value->date . "\"";
    if ($ch_count != $i)
        $amount_label .= ",";
    $i++;
}
$charts = $ipp->Charts();
$ch_count = count((array)$charts);
$i = 1;
$amount_data_set = "";
foreach ($charts as $value) {
    $amount_data_set .= ($value->amount->approved/100);
    if ($ch_count != $i)
        $amount_data_set .= ",";
    $i++;
}
$charts = $ipp->Charts();
$ch_count = count((array)$charts);
$i = 1;
$amount_data_set_decline = "";
foreach ($charts as $value) {
    $amount_data_set_decline .= ($value->amount->declined/100);
    if ($ch_count != $i)
        $amount_data_set_decline .= ",";
    $i++;
}

$inline_script = ["
    const ctx = document.getElementById('chart_tnx').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                ".$tnx_labels."
            ],
            datasets: [{
                label: 'Tnx past 30 days',
                backgroundColor: 'rgba(161, 198, 247, 1)',
                borderColor: 'rgb(47, 128, 237)',
                data: [".$tnx_data_set."],
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                    }
                }]
            }
        },
    });
    const camount = document.getElementById(\"chart_amount\").getContext('2d');
    const myAmoChart = new Chart(camount, {
        type: 'line',
        data: {
            labels: [".$amount_label."],
            datasets: [{
                label: 'Approved amount',
                backgroundColor: 'rgba(161, 198, 247, 0.3)',
                borderColor: 'rgb(47, 128, 237)',
                fillOpacity: '.3',
                data: [$amount_data_set],
            },{
                label: 'Declined amount',
                backgroundColor: 'rgba(255, 0, 0, 0.3)',
                borderColor: 'rgb(255, 0, 0)',
                fillOpacity: .3,
                data: [$amount_data_set_decline],
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                    }
                }]
            }
        },
    });
"];

echo foot();