
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
echo head();

echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
';
$i=1;
foreach($all_plugins as $key=>$value) {
    $name = $value->name;
    echo '
        <div class="col">
          <div class="card shadow-sm">
              <p class="card-header">'.$value->name.'</p>
            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
            <div class="card-body plugin-card-body">
              <p class="card-text">'.substr($value->description->en_gb->description, 0, 199); if(strlen($value->description->en_gb->description) > 199){ echo '...'; } echo '</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">';
                if(!file_exists(BASEDIR . "plugins/".$key))
                        echo '<button type="button" data-plugin-name="'.$key.'" data-plugin-file="'.$value->file.'" class="btn btn-sm btn-success installModal me-2">'.$lang["PARTNER"]["PLUGINS"]["INSTALL"].'</button>';
                else
                    echo '<button type="button" data-plugin-id="'.$plugins->getSettingsValues($key,"plugin_id").'" data-plugin-name="'.$key.'" class="btn btn-sm btn-danger me-2 removeModal">'.$lang["PARTNER"]["PLUGINS"]["UNINSTALL"].'</button>';
                ;
                echo
                  "<button type='button' class='btn btn-sm btn-info text-white' data-bs-toggle='modal' data-bs-target='#pluginViewMoreModal".$i."'>".$lang["PARTNER"]["PLUGINS"]["VIEW_MORE"]."</button>";
                echo '
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="pluginViewMoreModal'.$i.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                '.$value->name.'
                <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                </div>
                <div class="modal-body" id="pluginViewMoreContent">
                <div class="col">
                <div class="card shadow-sm">
                    
                    <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
                    <div class="card-body">
                    <p class="card-text">'.$value->description->en_gb->description.'</p>
                    </div>
                </div>
                </div>    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">'.$lang["PARTNER"]["PLUGINS"]["CLOSE"].'</button>';
                    $plugins->getSettingsValues($key,"").'\'>'.$lang["PARTNER"]["PLUGINS"]["SETTINGS"].'</button>';
                    if(!file_exists(BASEDIR . "plugins/".$key))
                        echo '<button type="button" data-plugin-name="'.$key.'" data-plugin-file="'.$value->file.'" class="btn btn-sm btn-success installModal">'.$lang["PARTNER"]["PLUGINS"]["INSTALL"].'</button>';
                    else
                        echo '<button type="button" class="btn btn-sm btn-info text-white pluginSettingsModal me-2" data-plugin-name="'.$key.'" data-plugin-title="'.$value->name.'" data-fields=\''.$plugins->getSettingsFields($key).'\' data-values=\''.$plugins->getSettingsValues($key,"").'\'>'.$lang["PARTNER"]["PLUGINS"]["SETTINGS"].'</button>

                        <button type="button" data-plugin-id="'.$plugins->getSettingsValues($key,"plugin_id").'" data-plugin-name="'.$key.'" class="btn btn-sm btn-danger removeModal">'.$lang["PARTNER"]["PLUGINS"]["UNINSTALL"].'</button>';
                    echo '
                </div>
            </div>
        </div>
    </div>
        ';
   $i++;
}
echo "</div>";
echo foot();