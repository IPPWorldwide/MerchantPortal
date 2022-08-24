<?php
include("../base.php");
if(isset($REQ["external"])) {
    if($plugins->hasExternalLogin($REQ["plugin_slug"])) {
        $REQ = array_merge($REQ, $plugins->hasExternalCommunication($REQ["plugin_slug"],"initial",$REQ));
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
    }
    header("Location: ".$REQ["return"]);
    die();
}
if(isset($REQ["plugin_slug"])) {
    $data_fields = $plugins->available_plugins[$REQ["plugin_slug"]]->getFields();

    $myfile = fopen(BASEDIR . "plugins/".$REQ["plugin_slug"]."/settings.php", "w") or die("Unable to open file!");
    $txt = "<?php\n";
    fwrite($myfile, $txt);
    foreach($REQ as $key=>$value) {
        $partner->UpdatePluginSettings($REQ["plugin_id"],$key,$value);
        $txt = "\$settings[\"".$key."\"] = '" . $value . "';\n";
        fwrite($myfile, $txt);
    }
    foreach($data_fields as $value) {
        if(isset($value["type"]) && $value["type"] === "file") {
            if(isset($_FILES[$value["id"]]['tmp_name'])) {
                $file = $_FILES[$value["id"]]['tmp_name'];
                $file_data = base64_encode(file_get_contents($file));
                if($file_data !== "") {
                    $partner->UpdatePluginSettings($REQ["plugin_id"],$value["id"],$file_data);
                    fwrite($myfile, "\$settings[\"".$value["id"]."\"] = '" . $file_data . "';\n");
                }
            }
        }
    }
    fclose($myfile);
    $update_plugin = new $REQ["plugin_slug"]();
    if(method_exists($update_plugin,"hookUpdate"))
        $update_plugin->hookUpdate($REQ["plugin_slug"],$REQ["plugin_id"],$REQ);
    echo json_encode($REQ);
    die();
}
$all_plugins = array_merge((array)$plugins->getAvailablePlugins(true),(array)$ipp->ListPlugins());


echo head();

echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 allplugins">
';
$i=1;
foreach($all_plugins as $key=>$value) {
    $name = $value->name ?? $value->id;
    $description = $value->description->en_gb->description ?? "";
    $file = $value->file ?? "";
    if(isset($value->admin_links))
        $admin_links = json_encode((object)$value->admin_links);
    else
        $admin_links = "{}";
    echo '
        <div class="col" data-plugin-id="'.$key.'">
          <div class="card shadow-sm">
              <p class="card-header">'.$name.'</p>
            ';
    if($value->image !== NULL) {
        echo '<div class="plugin-thumbnail"><img src="'.$value->image.'"></div>';
    } else {
        echo '<svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>';
    }
    echo '
            <div class="card-body plugin-card-body">
              <p class="card-text">'.substr($description, 0, 199); if(strlen($description) > 199){ echo '...'; } echo '</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">';
        echo '<button type="button" data-plugin-name="'.$key.'" data-plugin-key="'.$i.'" data-plugin-file="'.$file.'"'; if(file_exists(BASEDIR . "plugins/".$key)) { echo "style='display:none'"; } echo ' class="btn btn-sm btn-success installModal me-2">'.$lang["PARTNER"]["PLUGINS"]["INSTALL"].'</button>';
        echo '<button type="button" data-plugin-id="'.$plugins->getSettingsValues($key,"plugin_id").'" data-plugin-key="'.$i.'" data-plugin-name="'.$key.'"'; if(!file_exists(BASEDIR . "plugins/".$key)) { echo "style='display:none'"; } echo ' class="btn btn-sm btn-danger me-2 removeModal">'.$lang["PARTNER"]["PLUGINS"]["UNINSTALL"].'</button>';

    echo
        '<button type="button" class="btn btn-sm btn-info text-white btnShowMore" data-name="'.$name.'" data-slug="'.$key.'" data-admin-links=\''.$admin_links.'\' data-plugin-file="'.$file.'" data-external-login="'.$plugins->hasExternalLogin($key).'" data-installed="'.file_exists(BASEDIR . "plugins/".$key).'" data-description="'.strip_tags($description).'" data-fields=\''.$plugins->getSettingsFields($key).'\' data-values=\''.$plugins->getSettingsValues($key,'').'\' data-bs-toggle="modal" data-bs-target="#pluginViewMoreModal">'.$lang["PARTNER"]["PLUGINS"]["VIEW_MORE"].'</button>';
    echo '
                </div>
    ';
    if($description == "")
        echo '<div>Only local</div>';
    echo '
              </div>
            </div>
          </div>
        </div>
';
    $i++;
}
echo '
    <div class="modal fade" id="pluginViewMoreModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                </div>
                <div class="modal-body" id="pluginViewMoreContent">
                <div class="col">
                <div class="card shadow-sm">
                    
                    <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
                    <div class="card-body">
                    <p class="card-text"></p>
                    </div>
                </div>
                </div>    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">'.$lang["PARTNER"]["PLUGINS"]["CLOSE"].'</button>';

    echo '<a href="" class="btn btn-sm btn-info text-white me-2 ExternalLogin">'.$lang["PARTNER"]["PLUGINS"]["PERFORM_EXTERNAL_LOGIN"].'</a>';

echo '<button type="button" class="btn btn-sm btn-info text-white pluginSettingsModal me-2" data-plugin-name="" data-plugin-title="" data-bs-dismiss="modal" data-fields=\'\' data-values=\'\'>'.$lang["PARTNER"]["PLUGINS"]["SETTINGS"].'</button>';

    echo '<button type="button" data-plugin-name="" data-plugin-file="" class="btn btn-sm btn-success installModal">'.$lang["PARTNER"]["PLUGINS"]["INSTALL"].'</button>';

echo '<button type="button" data-plugin-id="" data-plugin-key="" data-plugin-name="" class="btn btn-sm btn-danger removeModal">'.$lang["PARTNER"]["PLUGINS"]["UNINSTALL"].'</button>';
echo '
                </div>
            </div>
        </div>
    </div>
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
                    <button type="button" class="btn btn-secondary closeModal" data-bs-dismiss="modal">'.$lang["PARTNER"]["PLUGINS"]["CLOSE"].'</button>
                    <button type="button" class="btn btn-primary confirmPluginSettngs">'.$lang["PARTNER"]["PLUGINS"]["SAVE"].'</button>
                </div>
            </div>
        </div>
    </div>';
echo "</div>";
$load_script[] = "https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js";
echo foot();