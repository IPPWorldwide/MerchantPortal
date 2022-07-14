<?php
include("../b.php");
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
$i=1;
?>
    <div class="col" data-plugin-id="smtp_server">
        <div class="card shadow-sm">
            <p class="card-header">Add new plugin</p>
            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
            <div class="card-body plugin-card-body">
                <p class="card-text"></p>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group"><form id="add-new-plugin" action="#" method="POST" enctype='multipart/form-data'><input type="file" name="new-plugin" id="plugin-file" accept=".zip"></form><button type="button" class="btn btn-sm btn-info text-white" id="upload-plugin-file">Upload</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script>
        $(document).ready(function (){
            $('#upload-plugin-file').click(function (){
                var vidFileLength = $("#plugin-file")[0].files.length;
                if(vidFileLength === 0){
                    swal({
                        title: "Error!",
                        text: "Please choose a file!",
                        icon: "error",
                    });
                }else{
                    var fd = new FormData();
                    var files = $('#plugin-file')[0].files;
                    fd.append('new-plugin',files[0]);
                    $.ajax({
                        url: 'plugin-validation.php',
                        type: 'post',
                        data: fd,
                        contentType: false,
                        processData: false,
                        success: function(response){
                            var d = JSON.parse(response);
                            if(d.error){
                                swal({
                                    title: "Error!",
                                    text: d.message,
                                    icon: "error",
                                });
                            }else{
                                swal({
                                    title: "Success!",
                                    text: d.message,
                                    icon: "success",
                                }).then(function (){
                                    window.location.reload();
                                });
                            }
                        },
                    });
                }
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<?php
foreach($all_plugins as $key=>$value) {
    // removing plugnings that are stored in remote api.
    unset($all_available_plugins[$key]);

    echo '
        <div class="col" data-plugin-id="'.$key.'">
          <div class="card shadow-sm">
              <p class="card-header">'.$value->name.'</p>
            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
            <div class="card-body plugin-card-body">
              <p class="card-text">'.substr($value->description->en_gb->description, 0, 199); if(strlen($value->description->en_gb->description) > 199){ echo '...'; } echo '</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">';
    if(!file_exists(BASEDIR . "plugins/".$key))
        echo '<button type="button" data-plugin-name="'.$key.'" data-plugin-key="'.$i.'" data-plugin-file="'.$value->file.'" class="btn btn-sm btn-success installModal me-2 plugin-btn-'.$i.'">'.$lang["PARTNER"]["PLUGINS"]["INSTALL"].'</button>';
    else
        echo '<button type="button" data-plugin-id="'.$plugins->getSettingsValues($key,"plugin_id").'" data-plugin-key="'.$i.'" data-plugin-name="'.$key.'" class="btn btn-sm btn-danger me-2 removeModal plugin-btn-'.$i.'">'.$lang["PARTNER"]["PLUGINS"]["UNINSTALL"].'</button>';
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
    if($plugins->hasExternalLogin($key)) {
        echo '<a href="'.$plugins->hasExternalLogin($key).'" class="btn btn-sm btn-info text-white me-2">'.$lang["PARTNER"]["PLUGINS"]["PERFORM_EXTERNAL_LOGIN"].'</a>';
    }
    if(!file_exists(BASEDIR . "plugins/".$key))
        echo '<button type="button" data-plugin-name="'.$key.'" data-plugin-file="'.$value->file.'" class="btn btn-sm btn-success installModal plugin-btn-'.$i.'">'.$lang["PARTNER"]["PLUGINS"]["INSTALL"].'</button>';
    else
        echo '<button type="button" class="btn btn-sm btn-info text-white pluginSettingsModal me-2" data-plugin-name="'.$key.'" data-plugin-title="'.$value->name.'" data-bs-dismiss="modal" data-fields=\''.$plugins->getSettingsFields($key).'\' data-values=\''.$plugins->getSettingsValues($key,"").'\'>'.$lang["PARTNER"]["PLUGINS"]["SETTINGS"].'</button>

                        <button type="button" data-plugin-id="'.$plugins->getSettingsValues($key,"plugin_id").'" data-plugin-key="'.$i.'" data-plugin-name="'.$key.'" class="btn btn-sm btn-danger removeModal plugin-btn-'.$i.'">'.$lang["PARTNER"]["PLUGINS"]["UNINSTALL"].'</button>';
    echo '
                </div>
            </div>
        </div>
    </div>';
    $i++;
}

// Check if any plugins are locally installed!
if(!is_null($all_available_plugins)){
    // Loop throug all locally installed plugins that are not in api call!
    foreach($all_available_plugins as $key=>$value) {

        $fields = $value->fields[0];
        echo '
          <div class="col" data-plugin-id="'.$key.'">
            <div class="card shadow-sm">
                <p class="card-header">'.$fields['name'].'</p>
              <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
              <div class="card-body">
                <p class="card-text">'.$fields['description'].'</p>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-info pluginSettingsModal" data-plugin-name="'.$fields['name'].'" data-plugin-title="'.$fields['title'].'" data-fields=\''.$plugins->getSettingsFields($key).'\' data-values=\''.$plugins->getSettingsValues($key,"").'\'>'.$lang["PARTNER"]["PLUGINS"]["SETTINGS"].'</button>';
        if(!file_exists(BASEDIR . "plugins/".$key))
            echo '<button type="button" data-plugin-name="'.$key.'" data-plugin-file="" class="btn btn-sm btn-success installModal">'.$lang["PARTNER"]["PLUGINS"]["INSTALL"].'</button>';
        else
            echo '<button type="button" data-local-plugin="1" data-plugin-id="'.$plugins->getSettingsValues($key,"plugin_id").'" data-plugin-name="'.$fields['name'].'" class="btn btn-sm btn-danger removeModal">'.$lang["PARTNER"]["PLUGINS"]["UNINSTALL"].'</button>';
        echo '
                  </div>
                  <div>Only local</div>
                </div>
              </div>
            </div>
          </div>';
    }
}
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
                    <button type="button" class="btn btn-secondary closeModal" data-bs-dismiss="modal">'.$lang["PARTNER"]["PLUGINS"]["CLOSE"].'</button>
                    <button type="button" class="btn btn-primary confirmPluginSettngs">'.$lang["PARTNER"]["PLUGINS"]["SAVE"].'</button>
                </div>
            </div>
        </div>
    </div>';
echo "</div>";
echo foot();