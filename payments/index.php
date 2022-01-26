<?php
include("../base.php");

$transaction_data = $ipp->TransactionsData($REQ["id"]);

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
    <link href="payments.css" rel="stylesheet">
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
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/merchant_data/">
                            <span data-feather="home"></span>
                            Merchant data
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/onboarding/">
                            <span data-feather="home"></span>
                            Onboarding
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/payouts/">
                            <span data-feather="home"></span>
                            Payouts / Settlements
                        </a>
                    </li>
            </div>
        </nav>


    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <h2>Payment Data</h2>
        <div class="row row-cols-md-3 mb-3">
            <div class="col themed-grid-col">Status: <?php echo $transaction_data->status; ?></div>
            <div class="col themed-grid-col">Timestamp: <?php echo date("Y-m-d H:i:s", $transaction_data->timestamp); ?></div>
            <div class="col themed-grid-col">Action: <?php echo $transaction_data->method; ?></div>
        </div>
        <div class="col-6"></div>
        <div class="row row-cols-md-3 mb-3">
            <div class="col themed-grid-col">Card Holder</div>
            <div class="col themed-grid-col"><?php echo $transaction_data->card_data->cardholder;?></div>
            <div class="col themed-grid-col">Amount</div>
            <div class="col themed-grid-col"><?php echo $transaction_data->amount; ?></div>
            <div class="col themed-grid-col">Currency</div>
            <div class="col themed-grid-col"><?php echo $currency->currency($transaction_data->currency)[0]; ?></div>
        </div>
        <div class="col-6"></div>

        <h2>Acquirer Response</h2>
        <textarea class="form-control" rows="10"><?php echo $transaction_data->acquirer_data->response;?></textarea>


        <h2>Related Payments</h2>
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Timestamp</th>
                    <th scope="col">Method</th>
                    <th scope="col">Cardholder</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Currency</th>
                    <th scope="col">Status</th>
                </tr>
                </thead>
                <tbody>
                <?php

                foreach($ipp->TransactionsRelated($transaction_data->transaction_id) as $value) {
                    echo "<tr ";
                    if($value->result == "WAIT") {
                        echo "class='bg-info'";
                    }
                    if($value->result == "NOK") {
                        echo "class='bg-danger'";
                    }
                    echo ">
              <td><a href='/payments/?id=".$value->action_id."' class='btn btn-dark'>Info</a></td>
              <td>".date("Y-m-d H:i:s",$value->unixtimestamp)."</td>
              <td>".$value->method."</td>
              <td>".$value->cardholder."</td>
              <td>".number_format($value->amount/100,2,",",".")."</td>
              <td>".$currency->currency($value->currency)[0]."</td>
              <td>".$value->result."</td>
            </tr>";
                }
                ?>
                </tbody>
            </table>
        </div>


    </main>
  </div>
</div>


    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="payments.js"></script>
  </body>
</html>
