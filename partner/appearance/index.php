<?php
include("../b.php");
if(isset($REQ["IPPCONFIG"]['PORTAL_TITLE'])) {
    include(BASEDIR . "controller/IPPConfig.php");
    $config = new IPPConfig();
    if(!isset($REQ["IPPCONFIG"]['PORTAL_DEACTIVATE_SEARCH'])){
        $REQ["IPPCONFIG"]['PORTAL_DEACTIVATE_SEARCH'] = 0;
    }
    if(!isset($REQ["IPPCONFIG"]['PORTAL_DEACTIVATE_VIRTUAL_TERMINAL'])){
        $REQ["IPPCONFIG"]['PORTAL_DEACTIVATE_VIRTUAL_TERMINAL'] = 0;
    }
    if(!isset($REQ["IPPCONFIG"]['PORTAL_LOCAL_DEACTIVATE_REFUNDS'])){
        $REQ["IPPCONFIG"]['PORTAL_LOCAL_DEACTIVATE_REFUNDS'] = 0;
    }
    if(!isset($REQ["IPPCONFIG"]['PORTAL_LOCAL_DEACTIVATE_VOID'])){
        $REQ["IPPCONFIG"]['PORTAL_LOCAL_DEACTIVATE_VOID'] = 0;
    }
    if(!isset($REQ["IPPCONFIG"]['PORTAL_LOCAL_HIDE_TOTAL_VOLUME'])){
        $REQ["IPPCONFIG"]['PORTAL_LOCAL_HIDE_TOTAL_VOLUME'] = 0;
    }
    foreach($REQ["IPPCONFIG"] as $key=>$value) {
        $config->UpdateConfig($key,$value);
    }
    $config = $config->WriteConfig();
    unset($REQ["IPPCONFIG"]);
}
$all_themes = $partner->ListThemes();
$public_themes = $partner->ListPublicThemes();
echo head();

echo '

    <div class="row">
        <div class="col-6">
            <h2>'.$lang["PARTNER"]["APPEARANCE"]["HEADER"].'</h2>
        </div>
        <div class="col-6 text-end">
            <button class="btn btn-success btnSettings">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                    <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                    <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
                </svg>
            </button>
        </div>
    </div>
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
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
echo '<div class="modal fade" id="appearanceSettingsModal" tabindex="-1" role="dialog" aria-labelledby="appearanceSettingsModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceSettingsModalLongTitle">'.$lang["PARTNER"]["DATA"]["PORTAL_SETTINGS"].'</h5>
            </div>
            <div class="modal-body">
                <form action="?" method="POST" class="form">
                    <table class="table v-middle p-0 m-0 box" data-plugin="dataTable">
                        <thead>
                        <tr>
                            <th>'.$lang["PARTNER"]["DATA"]["SETTING_NAME"].'</th>
                            <th>'.$lang["PARTNER"]["DATA"]["SETTING_VALUE"].'</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>'.$lang["PARTNER"]["DATA"]["LOCAL_PORTAL_TITLE"].'</td>
                                <td><input type="input" class="form form-control" name="IPPCONFIG[PORTAL_TITLE]" value="'.$IPP_CONFIG["PORTAL_TITLE"].'"></td>
                            </tr>
                            <tr>
                                <td>'.$lang["PARTNER"]["DATA"]["LOCAL_DEACTIVATE_SEARCH"].'</td>
                                <td>
                                <label class="switch">
                                <input type="checkbox" class="form form-control"  name="IPPCONFIG[PORTAL_DEACTIVATE_SEARCH]" value="1"';if(isset($IPP_CONFIG["PORTAL_DEACTIVATE_SEARCH"]) && $IPP_CONFIG["PORTAL_DEACTIVATE_SEARCH"] === "1"){ echo 'checked'; }; echo '>
                                <span class="slider round" ></span>
                                </label>
                                </td>
                            </tr>
                            <tr>
                                <td>'.$lang["PARTNER"]["DATA"]["LOCAL_DEACTIVATE_VIRTUAL_TERMINAL"].'</td>
                                <td>
                                <label class="switch">
                                <input type="checkbox" class="form form-control"  name="IPPCONFIG[PORTAL_DEACTIVATE_VIRTUAL_TERMINAL]" value="1"';if(isset($IPP_CONFIG["PORTAL_DEACTIVATE_VIRTUAL_TERMINAL"]) && $IPP_CONFIG["PORTAL_DEACTIVATE_VIRTUAL_TERMINAL"] === "1"){ echo 'checked'; }; echo '>
                                <span class="slider round" ></span>
                                </label>
                                </td>
                            </tr>
                            <tr>
                                <td>'.$lang["PARTNER"]["DATA"]["LOCAL_DEACTIVATE_REFUNDS"].'</td>
                                <td>
                                <label class="switch">
                                <input type="checkbox" class="form form-control"  name="IPPCONFIG[PORTAL_LOCAL_DEACTIVATE_REFUNDS]" value="1"';if(isset($IPP_CONFIG["PORTAL_LOCAL_DEACTIVATE_REFUNDS"]) && $IPP_CONFIG["PORTAL_LOCAL_DEACTIVATE_REFUNDS"] === "1"){ echo 'checked'; }; echo '>
                                <span class="slider round" ></span>
                                </label>
                                </td>
                            </tr>
                            <tr>
                                <td>'.$lang["PARTNER"]["DATA"]["LOCAL_DEACTIVATE_VOIDS"].'</td>
                                <td>
                                <label class="switch">
                                <input type="checkbox" class="form form-control"  name="IPPCONFIG[PORTAL_LOCAL_DEACTIVATE_VOID]" value="1"';if(isset($IPP_CONFIG["PORTAL_LOCAL_DEACTIVATE_VOID"]) && $IPP_CONFIG["PORTAL_LOCAL_DEACTIVATE_VOID"] === "1"){ echo 'checked'; }; echo '>
                                <span class="slider round" ></span>
                                </label>
                                </td>
                            </tr>
                            <tr>
                                <td>'.$lang["PARTNER"]["DATA"]["LOCAL_HIDE_TOTAL_VOLUME"].'</td>
                                <td>
                                <label class="switch">
                                <input type="checkbox" class="form form-control"  name="IPPCONFIG[PORTAL_LOCAL_HIDE_TOTAL_VOLUME]" value="1"';if(isset($IPP_CONFIG["PORTAL_LOCAL_HIDE_TOTAL_VOLUME"]) && $IPP_CONFIG["PORTAL_LOCAL_HIDE_TOTAL_VOLUME"] === "1"){ echo 'checked'; }; echo '>
                                <span class="slider round" ></span>
                                </label>
                                </td>
                            </tr> 
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closeModal">'.$lang["PARTNER"]["INVOICES"]["MODAL_CLOSE"].'</button>
                <button type="button" class="btn btn-primary confirm">'.$lang["PARTNER"]["INVOICES"]["MODAL_SUBMIT_BTN"].'</button>
            </div>
        </div>
    </div>
</div>
    ';
$load_script[] = "https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js";
echo foot();