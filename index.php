<?php
$public_page = true;
include("base.php");

if(isset($REQ["setup"])) {
    $utils->rrmdir("setup");
}
if(
        isset($_POST["username"]) && strlen($_POST["username"]) > 2 &&
        isset($_POST["password"]) && strlen($_POST["password"]) > 2
) {
    if(isset($_POST["administrator"]) && $_POST["administrator"] == "1") {
        $login = $partner->login($_POST["username"],$_POST["password"]);

        $url    = "/partner/";
        $login_type = "partner";
    } else {
        $login = $ipp->login($_POST["username"],$_POST["password"]);
        $url    = "/dashboard/";
        $login_type = "customer";
    }
    if($login->success) {
        setcookie("ipp_type",  $login_type, time()+3600);  /* expire in 1 hour */
        setcookie("ipp_id", $login->content->user_id, time()+3600);  /* expire in 1 hour */
        setcookie("ipp_session_id", $login->content->session_id, time()+3600);  /* expire in 1 hour */
        header("location: $url");
        exit;
    }
}

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.88.1">
    <title>Signin Template · Bootstrap v5.1</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/sign-in/">

    

    <!-- Bootstrap core CSS -->
<link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">

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
    <link href="signin.css" rel="stylesheet">
    <?php
    if(!is_null($plugins->hook_login)) {
        foreach($plugins->hook_login as $value) {
            echo $value;
        }
    }
    ?>
  </head>
  <body class="text-center">
    
<main class="form-signin">
  <form method="post" action="index.php">
    <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

    <div class="form-floating">
      <input type="email" class="form-control" id="floatingInput" name="username" placeholder="name@example.com">
      <label for="floatingInput">Email address</label>
    </div>
    <div class="form-floating">
      <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
      <label for="floatingPassword">Password</label>
    </div>

    <div class="checkbox mb-3">
      <label>
        <input name="administrator" type="checkbox" value="1"> Login as Payfac
      </label>
    </div>
    <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
  </form>
</main>


    
  </body>
</html>
