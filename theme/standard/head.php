<?php
function head() {
    return '<html lang="en">
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
  <div class="navbar-nav">
    <div class="nav-item text-nowrap">
      <a class="nav-link px-3" href="/logout.php">Sign out</a>
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
                <a class="nav-link active" aria-current="page" href="/subscriptions/">
                    <span data-feather="home"></span>
                    Subscriptions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="/virtual_terminal/">
                    <span data-feather="home"></span>
                    Virtual Terminal
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
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="/users/">
                    <span data-feather="home"></span>
                    Users
                </a>
            </li>
      </div>
    </nav>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

';
}