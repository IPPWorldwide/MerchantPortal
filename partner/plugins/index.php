<?php
include("../b.php");
if(isset($REQ["plugin_slug"])) {
    $myfile = fopen(BASEDIR . "plugins/".$REQ["plugin_slug"]."/settings.php", "w") or die("Unable to open file!");
    $txt = "<?php\n";
    fwrite($myfile, $txt);
    foreach($REQ as $key=>$value) {
        $partner->UpdatePluginSettings($REQ["plugin_id"],$key,$value);
        $txt = "\$settings[\"".$key."\"] = '" . $value . "';\n";
        fwrite($myfile, $txt);
    }
    fclose($myfile);
    $plugins->hookUpdate($REQ["plugin_slug"],$REQ["plugin_id"],$REQ);
    echo json_encode($REQ);
    die();
}
$all_plugins = $partner->ListPlugins();
echo head();

echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
';
foreach($all_plugins as $key=>$value) {
    echo '
        <div class="col" data-plugin-id="'.$key.'">
          <div class="card shadow-sm">
              <p class="card-header">'.$value->name.'</p>
            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
            <div class="card-body">
              <p class="card-text">'.$value->description->en_gb->description.'</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-info pluginSettingsModal" data-plugin-name="'.$key.'" data-plugin-title="'.$value->name.'" data-fields=\''.$plugins->getSettingsFields($key).'\' data-values=\''.$plugins->getSettingsValues($key,"").'\'>Settings</button>';
    if(!file_exists(BASEDIR . "plugins/".$key))
        echo '<button type="button" data-plugin-name="'.$key.'" data-plugin-file="'.$value->file.'" class="btn btn-sm btn-success installModal">Install</button>';
    else
        echo '<button type="button" data-plugin-id="'.$plugins->getSettingsValues($key,"plugin_id").'" data-plugin-name="'.$key.'" class="btn btn-sm btn-danger removeModal">Remove</button>';
    echo '
                </div>
              </div>
            </div>
          </div>
        </div>';
}
echo "</div>";
?>

    <div class="modal fade" id="pluginModal" tabindex="-1" role="dialog" aria-labelledby="pluginModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                </div>
                <div class="modal-body">
                    <form id="pluginSettings">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeModal">Close</button>
                    <button type="button" class="btn btn-primary confirmPluginSettngs">Save changes</button>
                </div>
            </div>
        </div>
    </div>
<?php
echo foot();