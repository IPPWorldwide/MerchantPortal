<?php
include("../b.php");
$all_themes = $partner->ListThemes();
echo head();

echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
';
foreach($all_themes as $key=>$value) {
    $description = "";
    if(file_exists($value."/info.php"))
        include $value."/info.php";

    echo '
        <div class="col">
          <div class="card shadow-sm">
              <p class="card-header">'.basename($value).'</p>
            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
            <div class="card-body">
              <p class="card-text">';
                echo $description . '</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">';
    if($IPP_CONFIG["THEME"] !== basename($value))
        echo '<button type="button" data-plugin-name="'.basename($value).'" class="btn btn-sm btn-success installModal">'.$lang["PARTNER"]["PLUGINS"]["INSTALL"].'</button>';
    else
        echo '<button type="button" data-plugin-name="'.basename($value).'" class="btn btn-sm btn-success installModal btnHidden">'.$lang["PARTNER"]["PLUGINS"]["INSTALL"].'</button>';
    echo '
                </div>
              </div>
            </div>
          </div>
        </div>';
}
echo "</div>";
echo foot();