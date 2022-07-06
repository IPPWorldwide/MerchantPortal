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
    if(method_exists($plugins,"hookUpdate"))
        $plugins->hookUpdate($REQ["plugin_slug"],$REQ["plugin_id"],$REQ);
    echo json_encode($REQ);
    die();
}
$all_plugins = $partner->ListPlugins();
$all_available_plugins = $plugins->getAvailablePlugins();
echo head();

echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
';
foreach($all_plugins as $key=>$value) {
    // removing plugnings that are stored in remote api.
    unset($all_available_plugins[$key]);
    echo '
        <div class="col" data-plugin-id="'.$key.'">
          <div class="card shadow-sm">
              <p class="card-header">'.$value->name.'</p>
            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
            <div class="card-body">
              <p class="card-text">'.$value->description->en_gb->description.'</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-info pluginSettingsModal" data-plugin-name="'.$key.'" data-plugin-title="'.$value->name.'" data-fields=\''.$plugins->getSettingsFields($key).'\' data-values=\''.$plugins->getSettingsValues($key,"").'\'>'.$lang["PARTNER"]["PLUGINS"]["SETTINGS"].'</button>';
    if(!file_exists(BASEDIR . "plugins/".$key))
        echo '<button type="button" data-plugin-name="'.$key.'" data-plugin-file="'.$value->file.'" class="btn btn-sm btn-success installModal">'.$lang["PARTNER"]["PLUGINS"]["INSTALL"].'</button>';
    else
        echo '<button type="button" data-local-plugin="0" data-plugin-id="'.$plugins->getSettingsValues($key,"plugin_id").'" data-plugin-name="'.$key.'" class="btn btn-sm btn-danger removeModal">'.$lang["PARTNER"]["PLUGINS"]["UNINSTALL"].'</button>';
    echo '
                </div>
              </div>
            </div>
          </div>
        </div>';
}
// Check if any plugins are locally installed!
if(!is_null($all_available_plugins)){
  // Loop throug all locally installed plugins that are not in api call!
  foreach($all_available_plugins as $key=>$value) {
      $informations = $value->getFields()[0];
      echo '
          <div class="col" data-plugin-id="'.$key.'">
            <div class="card shadow-sm">
                <p class="card-header">'.$value->id.'</p>
              <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
              <div class="card-body">
                <p class="card-text"></p>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-info pluginSettingsModal" data-plugin-name="'.$key.'" data-plugin-title="'.$value->id.'" data-fields=\''.$plugins->getSettingsFields($key).'\' data-values=\''.$plugins->getSettingsValues($key,"").'\'>'.$lang["PARTNER"]["PLUGINS"]["SETTINGS"].'</button>';
      if(!file_exists(BASEDIR . "plugins/".$key))
          echo '<button type="button" data-plugin-name="'.$key.'" data-plugin-file="" class="btn btn-sm btn-success installModal">'.$lang["PARTNER"]["PLUGINS"]["INSTALL"].'</button>';
      else
          echo '<button type="button" data-local-plugin="1" data-plugin-id="'.$plugins->getSettingsValues($key,"plugin_id").'" data-plugin-name="'.$key.'" class="btn btn-sm btn-danger removeModal">'.$lang["PARTNER"]["PLUGINS"]["UNINSTALL"].'</button>';
      echo '
                  </div>
                  <div>Only local</div>
                </div>
              </div>
            </div>
          </div>';
  }
}
echo "</div>";
echo '
    <div class="modal fade" id="pluginModal" tabindex="-1" role="dialog" aria-labelledby="pluginModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                </div>
                <div class="modal-body">
                    <form id="pluginSettings">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeModal">'.$lang["PARTNER"]["PLUGINS"]["CLOSE"].'</button>
                    <button type="button" class="btn btn-primary confirmPluginSettngs">'.$lang["PARTNER"]["PLUGINS"]["SAVE"].'</button>
                </div>
            </div>
        </div>
    </div>
';
echo foot();