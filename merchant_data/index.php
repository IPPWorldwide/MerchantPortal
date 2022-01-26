<?php
include("../base.php");
if(isset($REQ["id"])) {
    $merchant_data = $ipp->MerchantDataUpdate($REQ);
}
$merchant_data = $ipp->MerchantData();
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
        <form action="?" method="POST" class="form">
            <h2>Merchant Data</h2>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">Merchant ID:<br /><input name="id" class="form-control" value="<?php echo $merchant_data->id; ?>" readonly></div>
                <div class="col themed-grid-col">API Password:<br /><input name="security[key1]" class="form-control" value="<?php echo $merchant_data->security->key1; ?>"></div>
                <div class="col themed-grid-col">Payment Password:<br /><input name="security[key2]" class="form-control" value="<?php echo $merchant_data->security->key2; ?>"></div>
            </div>
            <h2>Company Details</h2>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">Company Name:<br /><input name="meta[company][name]" class="form-control" value="<?php echo isset($merchant_data->meta_data->company->name) ? $merchant_data->meta_data->company->name : ""; ?>"></div>
                <div class="col themed-grid-col">Company Registration No:<br /><input name="meta[company][reg_id]" class="form-control" value="<?php echo isset($merchant_data->meta_data->company->reg_id) ? $merchant_data->meta_data->company->reg_id : ""; ?>"></div>
                <div class="col themed-grid-col">EU VAT Number:<br /><input name="meta[company][vat]" class="form-control" value="<?php echo isset($merchant_data->meta_data->company->vat) ? $merchant_data->meta_data->company->vat : ""; ?>"></div>
            </div>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">Street Address:<br /><input name="meta[address][address]" class="form-control" value="<?php echo isset($merchant_data->meta_data->address->address) ? $merchant_data->meta_data->address->address : ""; ?>"></div>
                <div class="col themed-grid-col">Postal:<br /><input name="meta[address][postal]" class="form-control" value="<?php echo isset($merchant_data->meta_data->address->postal) ? $merchant_data->meta_data->address->postal : ""; ?>"></div>
                <div class="col themed-grid-col">City:<br /><input name="meta[address][city]" class="form-control" value="<?php echo isset($merchant_data->meta_data->address->city) ? $merchant_data->meta_data->address->city : ""; ?>"></div>
            </div>
            <div class="row row-cols-md-3 mb-3">
                <div class="col themed-grid-col">Country:<br /><input name="meta[address][country]" class="form-control" value="<?php echo isset($merchant_data->meta_data->address->country) ? $merchant_data->meta_data->address->country : ""; ?>"></div>
                <div class="col themed-grid-col">Phone number:<br /><input name="meta[company][phone]" class="form-control" value="<?php echo isset($merchant_data->meta_data->company->phone) ? $merchant_data->meta_data->company->phone : ""; ?>"></div>
                <div class="col themed-grid-col">Cardholder Description:<br /><input name="meta[processing][descriptor]" class="form-control" value="<?php echo isset($merchant_data->meta_data->processing->descriptor) ? $merchant_data->meta_data->processing->descriptor : ""; ?>"></div>
            </div>
            <div class="row row-cols-md-2 mb-2">
                <div class="col themed-grid-col">
                    <h2>Acquirers</h2>
                    <table class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>ID</th>
                            <th>Website</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($merchant_data->acquirers as $key=>$value) {
                            echo "<tr><td>".$value->name."</td><td>".$value->id."</td><td></td><td><a href='".$value->url."' target='_BLANK'>".$value->url."</a></td></tr>";
                        } ?>
                        </tbody>
                    </table>
                </div>
                <div class="col themed-grid-col">
                    <div class="box-header">
                        <h2>Acquirers Rules</h2>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="radio">
                                    <label class="ui-check ui-check-lg">
                                        <input type="radio" name="rule_type" value="AABB-ABCD-ABCD" <?php if($merchant_data->rules->id == "AABB-ABCD-ABCD") { echo "checked"; } ?>>
                                        <i class="dark-white"></i>
                                        Round Robin
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="radio">
                                    <label class="ui-check ui-check-lg">
                                        <input type="radio" name="rule_type" value="BBBB-CCCC-DDDD" <?php if($merchant_data->rules->id == "BBBB-CCCC-DDDD") { echo "checked"; } ?>>
                                        <i class="dark-white"></i>
                                        Round Robin with max limit
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="radio">
                                    <label class="ui-check ui-check-lg">
                                        <input type="radio" name="rule_type" value="CCCC-DDDD-EEEE" <?php if($merchant_data->rules->id == "CCCC-DDDD-EEEE") { echo "checked"; } ?>>
                                        <i class="dark-white"></i>
                                        Acquirer with lowest processing volume today
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="radio">
                                    <label class="ui-check ui-check-lg">
                                        <input type="radio" name="rule_type" value="DDDD-EEEE-FFFF" <?php if($merchant_data->rules->id == "DDDD-EEEE-FFFF") { echo "checked"; } ?>>
                                        <i class="dark-white"></i>
                                        Acquirer with lowest processing volume, with max limit
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="box-header">
                            <h2>Rule settings</h2>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Daily max volume</th>
                                            <th>Supported brands</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach($merchant_data->acquirers as $value) {
                                            echo "<tr>";
                                            echo "<td>";
                                            echo $value->name;
                                            echo "</td>";
                                            if ($merchant_data->rules->id == "BBBB-CCCC-DDDD" || $merchant_data->rules->id == "DDDD-EEEE-FFFF") {
                                                echo "<td>";
                                                echo "<input name=\"rules[".$value->id."][daily_limit]\" type=\"text\" class=\"form-control\"";
                                                if(isset($merchant_data->rules->acquirer_rules->{$value->id}->max_limit)) {
                                                    echo " value=\"".$merchant_data->rules->acquirer_rules->{$value->id}->max_limit."\"";
                                                } else {
                                                    echo "";
                                                }
                                                echo ">";
                                                echo "</td>";
                                            }
                                            if ($merchant_data->rules->id == "CCCC-DDDD-EEEE") {

                                            }
                                            echo "</tr>";
                                        }
                                        ?>
                                        </tbody></table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row-cols-md-1 mb-1">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mb-3">Save</button>
                </div>

            </div>
        </form>
    </main>
  </div>
</div>


    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
  </body>
</html>
