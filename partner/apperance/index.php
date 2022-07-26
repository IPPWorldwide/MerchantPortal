<?php
include("../b.php");
$all_themes = $partner->ListThemes();
$public_themes = $partner->ListPublicThemes();
echo head();

echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
    <div class="col">
        <div class="card shadow-sm">
            <p class="card-header">Add new theme</p>
            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
            <div class="card-body plugin-card-body">
                <p class="card-text"></p>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group"><form id="add-new-plugin" action="#" method="POST" enctype="multipart/form-data"><input type="file" name="new-theme" id="theme-file" accept=".zip"></form><button type="button" class="btn btn-sm btn-info text-white" id="upload-theme-file">Upload</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
';
foreach($all_themes as $key=>$value) {
    $description = "";
    if(file_exists($value."/info.php"))
        include $value."/info.php";
    echo '
        <div class="col" id="theme_'.basename($value).'">
          <div class="card shadow-sm">
              <p class="card-header">'.basename($value).'</p>
            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
            <div class="card-body">
              <p class="card-text">';
                echo $description . '</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">';
        echo '<button type="button" data-plugin-name="'.basename($value).'" class="btn btn-sm btn-success'; if($IPP_CONFIG["THEME"] === basename($value)) { echo " btnHidden"; } echo ' installModal">'.$lang["PARTNER"]["APPEARANCE"]["INSTALL"].'</button>';
        echo '&nbsp;<button type="button" data-plugin-name="'.basename($value).'" class="btn btn-sm btn-warning removeTheme">'.$lang["PARTNER"]["APPEARANCE"]["REMOVE"].'</button>';
    echo '
                </div>
              </div>
            </div>
          </div>
        </div>';
}
echo "</div>";

echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">';
foreach($public_themes as $key=>$value) {
    $description = htmlentities($value->description->en_gb->description);
    $teaser = $value->description->en_gb->teaser;
    echo '
        <div class="col" id="theme_'.$key.'">
          <div class="card shadow-sm">
              <p class="card-header">'.$value->name.'</p>
            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
            <div class="card-body">
              <p class="card-text" data-description="'.$description.'">'.substr($teaser, 0, 199); if(strlen($teaser) > 199){ echo '...'; } echo '</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">';
    echo '<button type="button" data-slug="'.$key.'" data-theme-monthly-cost="'.$value->cost.'" data-theme-name="'.$lang["PARTNER"]["APPEARANCE"]["PURCHASE"].' '.$value->name.'" class="btn btn-sm btn-success purchaseModal">'.$lang["PARTNER"]["APPEARANCE"]["PURCHASE_THEME"].'</button>';
    echo '
                </div>
              </div>
            </div>
          </div>
        </div>';
}
echo "</div>";
echo '<div class="modal fade" id="purchaseModal" tabindex="-1" role="dialog" aria-labelledby="purchaseModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <div>
                    '.$lang["PARTNER"]["APPEARANCE"]["MONTHLY_COST"].' <span class="monthly_price"></span> EUR                    
                    </div>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeModal">'.$lang["PARTNER"]["APPEARANCE"]["CLOSE"].'</button>
                    <button type="button" class="btn btn-primary confirmPurchase" data-slug="">'.$lang["PARTNER"]["APPEARANCE"]["BTN_PURCHASE"].'</button>
                </div>
            </div>
        </div>
    </div>';
$load_script[] = "https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js";
echo foot();