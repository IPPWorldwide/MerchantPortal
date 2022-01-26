<?php
include("../base.php");
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.88.1">
    <title>Dashboard Template · Bootstrap v5.1</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/dashboard/">

    

    <!-- Bootstrap core CSS -->
<link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    
    <!-- Custom styles for this template -->
    <link href="dashboard.css" rel="stylesheet">
  </head>
  <body>
    
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Company name</a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
  <div class="navbar-nav">
    <div class="nav-item text-nowrap">
      <a class="nav-link px-3" href="#">Sign out</a>
    </div>
  </div>
</header>

<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/dashboard/">
              <span data-feather="home"></span>
              Dashboard
            </a>
          </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="/charts/">
                    <span data-feather="home"></span>
                    Charts
                </a>
            </li>
      </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

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


    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="dashboard.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script>

<style>
    .chart-container {
        width: 50%;
        height: 50%;
        margin: auto;
    }
</style>

<script>
    const ctx = document.getElementById("chart_tnx").getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                <?php
                $charts = $ipp->Charts();
                $ch_count = count((array)$charts);
                $i = 1;
                foreach ($charts as $value) {
                    echo "\"" . $value->date . "\"";
                    if ($ch_count != $i)
                        echo ",";
                    $i++;
                } ?>
            ],
            datasets: [{
                label: 'Tnx past 30 days',
                backgroundColor: 'rgba(161, 198, 247, 1)',
                borderColor: 'rgb(47, 128, 237)',
                data: [
                    <?php
                    $charts = $ipp->Charts();
                    $ch_count = count((array)$charts);
                    $i = 1;
                    foreach ($charts as $value) {
                        echo $value->count;
                        if ($ch_count != $i)
                            echo ",";
                        $i++;
                    } ?>],
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

    const camount = document.getElementById("chart_amount").getContext('2d');
    const myAmoChart = new Chart(camount, {
        type: 'line',
        data: {
            labels: [
                <?php
                $charts = $ipp->Charts();
                $ch_count = count((array)$charts);
                $i = 1;
                foreach ($charts as $value) {
                    echo "\"" . $value->date . "\"";
                    if ($ch_count != $i)
                        echo ",";
                    $i++;
                } ?>
            ],
            datasets: [{
                label: 'Approved amount past 30 days',
                backgroundColor: 'rgba(161, 198, 247, 0.3)',
                borderColor: 'rgb(47, 128, 237)',
                fillOpacity: '.3',
                data: [
                    <?php
                    $charts = $ipp->Charts();
                    $ch_count = count((array)$charts);
                    $i = 1;
                    foreach ($charts as $value) {
                        echo $value->amount->approved."0";
                        if ($ch_count != $i)
                            echo ",";
                        $i++;
                    } ?>],
            },{
                label: 'Declined amount past 30 days',
                backgroundColor: 'rgba(255, 0, 0, 0.3)',
                borderColor: 'rgb(255, 0, 0)',
                fillOpacity: .3,
                data: [
                    <?php
                    $charts = $ipp->Charts();
                    $ch_count = count((array)$charts);
                    $i = 1;
                    foreach ($charts as $value) {
                        echo $value->amount->declined;
                        if ($ch_count != $i)
                            echo ",";
                        $i++;
                    } ?>],
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
</script>

  </body>
</html>
