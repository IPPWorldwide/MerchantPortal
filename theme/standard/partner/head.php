<?php
function head() {
    global $plugins,$lang,$IPP_CONFIG;
    $hook_header = "";
    if(!is_null($plugins->hook_header)) {
        foreach($plugins->hook_header as $value) {
            $hook_header .= $value;
        }
    }
    $extra_css = "";
    if(file_exists("css.css"))
        $extra_css = "<link href=\"css.css\" rel=\"stylesheet\">";
    return "<!doctype html>
<html lang=\"en\">
  <head>
    <meta charset=\"utf-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
    <meta name=\"description\" content=\"\">
    <meta name=\"author\" content=\"Mark Otto, Jacob Thornton, and Bootstrap contributors\">
    <meta name=\"generator\" content=\"Hugo 0.88.1\">
    <title>".$IPP_CONFIG["PORTAL_TITLE"]."</title>

    <link rel=\"canonical\" href=\"https://getbootstrap.com/docs/5.1/examples/dashboard/\">

    

    <!-- Bootstrap core CSS -->
<link href=\"/assets/dist/css/bootstrap.min.css\" rel=\"stylesheet\">

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
    <link href=\"/partner/dashboard.css\" rel=\"stylesheet\">
    ".$extra_css."
    ".$hook_header."
  </head>
  <body>
    
<header class=\"navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow\">
  <a class=\"navbar-brand col-md-3 col-lg-2 me-0 px-3\" href=\"#\">".$IPP_CONFIG["PORTAL_TITLE"]."</a>
  <button class=\"navbar-toggler position-absolute d-md-none collapsed\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#sidebarMenu\" aria-controls=\"sidebarMenu\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
    <span class=\"navbar-toggler-icon\"></span>
  </button>
  <div class=\"navbar-nav\">
    <div class=\"nav-item text-nowrap\">
      <a class=\"nav-link px-3\" href=\"/logout.php\" id='lougout_btn'>".$lang["SIGN_OUT"]."</a>
    </div>
  </div>
</header>

<div class=\"container-fluid\">
  <div class=\"row\">
    <nav id=\"sidebarMenu\" class=\"col-md-3 col-lg-2 d-md-block bg-light sidebar collapse\">
      <div class=\"position-sticky pt-3\">

        <ul class=\"nav flex-column\">
            ".standard_theme_menu("partner", "partner/")."
      </div>
    </nav>
    <main class=\"col-md-9 ms-sm-auto col-lg-10 px-md-4\">
";
}