<?php
include("../b.php");
$load_css[] = "editor.css";
if(isset($REQ["theme"]) && isset($REQ["file"]) && isset($REQ["submit"])) {
    if(isset($REQ["file"]) && is_file(THEMES.$REQ["theme"] ."/". $REQ["file"])) {
        $myfile = fopen(THEMES.$REQ["theme"] ."/". $REQ["file"], "w") or die("Unable to open file!");
        fwrite($myfile, $REQ["fileContent"]);
        fclose($myfile);
        header("Location: editor.php?theme=".$REQ["theme"]."&file=".$REQ["file"]."&updated=1");
    }
    var_dump($REQ);
    die();
}
$REQ["file"] = $REQ["file"] ?? "head.php";
echo head();
$actions->get_action("partner_apperance_editor");
echo '
    <div class="row">
        <div class="col-6">
            <h2>'.$lang["PARTNER"]["APPEARANCE_EDITOR"]["HEADER"].'</h2>
        </div>
    </div>
    <div class="fullHeight row">
        <div class="col-9">
        <form method="post" action="?" class="fileEditor">
            <input type="hidden" name="theme" value="'.$REQ["theme"].'">
            <input type="hidden" name="file" value="'.$REQ["file"].'">
            <textarea name="fileContent" class="fileArea form-control">';
            if(isset($REQ["file"]) && is_file(THEMES.$REQ["theme"] ."/". $REQ["file"])) {
                $file = THEMES.$REQ["theme"] ."/". $REQ["file"];
                $fp = fopen($file, "r");
                while(!feof($fp)) {
                    $data = fgets($fp, filesize($file));
                    echo $data;
                }
                fclose($fp);
            }
            echo '</textarea>
            <input type="submit" class="btn btn-success" name="submit" value="'.$lang["PARTNER"]["APPEARANCE_EDITOR"]["SAVE"].'">
        </form>
        </div>
        <div class="col-3">
            <ul class="list-group">
              ';
                foreach(scandir(THEMES.$REQ["theme"], SCANDIR_SORT_DESCENDING) as $value) {
                    if($value === "." || $value === "..")
                        continue;
                    if(is_file(THEMES.$REQ["theme"] ."/". $value))
                        echo '<li class="list-group-item"><a href="?theme='.$REQ["theme"].'&file='.$value.'">'.$value.'</a></li>';
                }
                echo '
            </ul>
        </div>
    </div>
';
echo foot();
