<?php
function foot() {
    global $inline_css, $inline_script,$load_script,$plugins,$load_css;
    $css = "";
    $script = "";
    foreach($inline_css as $value) {
        $css .= "<style>".$value."</style>";
    }
    foreach($load_css as $value) {
        $css .= '<link href="'.$value.'" rel="stylesheet">';
    }
    foreach($inline_script as $value) {
        $script .= "<script>".$value."</script>";
    }
    foreach($load_script as $value) {
        $script .= "<script src='".$value."'></script>";
    }
    if(!is_null($plugins->hook_footer)) {
        foreach($plugins->hook_footer as $value) {
            $script .= $value;
        }
    }
    return "
    </main>
  </div>
</div>


<script src=\"https://code.jquery.com/jquery-3.3.1.min.js\" crossorigin=\"anonymous\"></script>
<script src=\"//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js\"></script>
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js\" integrity=\"sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut\" crossorigin=\"anonymous\"></script>
<script src=\"/assets/dist/js/bootstrap.bundle.min.js\"></script>

      <script src=\"https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js\" integrity=\"sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE\" crossorigin=\"anonymous\"></script><script src=\"https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js\" integrity=\"sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha\" crossorigin=\"anonymous\"></script><script src=\"dashboard.js\"></script>
    $css $script
  </body>
</html>
";
}