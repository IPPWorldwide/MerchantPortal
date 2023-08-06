<?php
$public_page = true;
include_once("base.php");
if(isset($REQ["timezone"])) {
    setcookie("timezone", $REQ["timezone"],strtotime("+1 day"));
    die();
}
if(isset($REQ["reset"])) {
    $data = $ipp->ConfirmResetUserPassword($IPP_CONFIG["PARTNER_ID"],$REQ["id"],$REQ["initialization"],$REQ["hash"]);
    if(isset($data->content->username)) {
        $REQ["username"] = $data->content->username;
        $REQ["password"] = $data->content->password;
    }
}
if(isset($REQ["reset_email"])) {
    $ipp->RequestResetUserPassword($IPP_CONFIG["PARTNER_ID"],$REQ["reset_email"],$IPP_CONFIG["PORTAL_URL"]);
    die();
}
if(isset($REQ["language"])) {
    setcookie("language", $REQ["language"],strtotime("+1 year"));
    header("Location: /");
    exit;
}
if(isset($REQ["setup"])) {
    if(is_dir("setup"))
        $utils->rrmdir("setup");
    if(is_dir("tmp"))
        $utils->rrmdir("tmp");
}
if(
        isset($REQ["username"]) && strlen($REQ["username"]) > 2 &&
        isset($REQ["password"]) && strlen($REQ["password"]) > 2
) {
    if(isset($REQ["administrator"]) && $REQ["administrator"] == "1") {
        $login = $partner->login($REQ["username"],$REQ["password"]);
        $url    = "/partner/";
        $login_type = "partner";
    } else {
        $login = $ipp->login($REQ["username"],$REQ["password"]);
        $url    = "/dashboard/";
        $login_type = "customer";
    }
    if($login->success) {
        setcookie("ipp_type",  $login_type, time()+3600);  /* expire in 1 hour */
        setcookie("ipp_user_id", $login->content->user_id, time()+3600);  /* expire in 1 hour */
        setcookie("ipp_user_session_id", $login->content->session_id, time()+3600,);  /* expire in 1 hour */
        header("location: $url");
        exit;
    }
}
if(isset($REQ["pages"])) {
    try {
        $page = new $REQ["pages"]();
        if(in_array($REQ["page"],$page->public_pages)) {
            $plugins->loadPage($REQ["pages"],$REQ["page"],$REQ);
        }
    } catch (Error $e) {
        var_dump($e);
        die();
    }
}
$actions->get_action("theme_replacement");
$actions->get_action("login");
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mathias Gajhede">
    <meta name="generator" content="IPP Gateway 1.0">
    <title><?php echo $IPP_CONFIG["PORTAL_TITLE"]; ?></title>

    <link rel="canonical" href="<?php echo $IPP_CONFIG["PORTAL_URL"]; ?>">

    

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
      <?php
      echo '
    <link href="'.THEME.'/assets/css/signin.css" rel="stylesheet">
    ';
    if(!is_null($plugins->hook_login)) {
        foreach($plugins->hook_login as $value) {
            echo $value;
        }
    }
    ?>
  </head>
  <body class="text-center">
  <div class="language">
      Change language:<br />
      <?php
      foreach($languages->getLanguages() as $value) {
          echo '<a href="?language='.$value["code"].'"><img width="32px" src="'.$value["img"].'"></a>';
      }
      ?>
  </div>
    <main class="form-signin">
      <form method="post" action="index.php">
        <h3 class="h3 mb-3 fw-normal"><?php echo $lang["LOGIN"]["HEADLINE"]; ?></h3>
          <?php
          if(isset($login->code) && $login->code === 401) {
              echo '<div class="alert alert-danger" role="alert">'.$lang["LOGIN"]["INCORRECT_USER_OR_PASSWORD"].'</div>';
          }
          if(isset($login->code) && $login->code === 4020) {
              echo '<div class="alert alert-danger" role="alert">'.$lang["LOGIN"]["ACCOUNT_UNAVAILABLE"].'</div>';
          }
          ?>
        <div class="form-floating">
          <input type="text" class="form-control" id="floatingInput" name="username" placeholder="name@example.com">
          <label for="floatingInput"><?php echo $lang["LOGIN"]["EMAIL"]; ?></label>
        </div>
        <div class="form-floating">
          <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
          <label for="floatingPassword"><?php echo $lang["LOGIN"]["PASSWORD"]; ?></label>
        </div>
        <div class="checkbox mb-3">
          <label>
            <input name="administrator" type="checkbox" value="1"> <?php echo $lang["LOGIN"]["AS_PAYFAC"]; ?>
          </label>
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="submit"><?php echo $lang["LOGIN"]["SIGN_IN"]; ?></button>
      </form><br />
        <?php
        if(isset($plugins->available_plugins["smtp_server"])) {
            echo '<a class="forgotPassword">Forgot password</a><br />';
        }
        ?>
    </main>
    <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                </div>
                <div class="modal-body">
                    <div class="ResetEmailSent">
                        <?php echo $lang["LOGIN"]["SENT_RESET_EMAIL"]; ?>
                    </div>
                    <form>
                        <input type="hidden" name="user_id" id="user-id" readonly>
                        <div class="form-group">
                            <input type="text" class="form-control"  name="username" id="username" placeholder="Email">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeModal"><?php echo $lang["LOGIN"]["CLOSE"]; ?></button>
                    <button type="button" class="btn btn-primary confirm"><?php echo $lang["LOGIN"]["RESET_PASSWORD"]; ?></button>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
    <script src="signin.js"></script>
    <script>
      $( document ).ready(function() {
          var timezone_offset_minutes = new Date().getTimezoneOffset();
          timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;
          $.post( "?", { timezone: timezone_offset_minutes })
          .done(function( data ) {
              console.log( "Timezone locked at " + timezone_offset_minutes + " minutes");
          });
      });
    </script>
  </body>
</html>
