<?php
include("../b.php");
if(isset($REQ["section"])) {
    $menu = [];
    foreach($REQ["menus"] as $value)
        $menu[$value["url"]] = $value["value"];
    $current_menu = json_decode($IPP_CONFIG["MENU"]);
    $current_menu->{strtoupper($REQ["section"])} = $menu;
    include(BASEDIR . "controller/IPPConfig.php");
    $config = new IPPConfig();
    $new_config = $config->UpdateConfig("MENU",json_encode($current_menu));
    $config = $config->WriteConfig();
    die();
}

$MENU = json_decode($IPP_CONFIG["MENU"]);

echo head();
$actions->get_action("partner_menus");
echo '
<h2>'.$lang["PARTNER"]["MENUS"]["HEADER_PARTNER"].'</h2>
<div class="row">
    <div class="col-12">
    <form method="GET" action="?">
        '.$lang["PARTNER"]["MENUS"]["SELECT_MENU"].'<select name="menu"><option value="company">'.$lang["PARTNER"]["MENUS"]["MENU_COMPANY"].'</option><option value="partner">'.$lang["PARTNER"]["MENUS"]["MENU_PARTNER"].'</option></select>
        <input type="submit" value="'.$lang["PARTNER"]["MENUS"]["SELECT_MENU_BUTTON"].'">
    </form>
    </div>
</div>
';
if(isset($REQ["menu"])) {
    echo '
<div class="row">
    <div class="col-2">
        <h5>'.$lang["PARTNER"]["MENUS"]["ADD_ELEMENTS"].'</h5>
        <form id="newElementsToMenu" method="POST">
            <table>
            ';
        foreach($menu->std_menu[strtoupper($REQ["menu"])] as $key=>$value) {
            echo "<tr><td><input type='checkbox' name='".$value."' value='".$key."'></td><td>".$value."<td></td></tr>";
        }
        echo '
            </table>
            <input name="submit" type="submit" value="'.$lang["PARTNER"]["MENUS"]["ADD_TO_MENU"].'" data-theme="'.THEME.'">
        </form>
    </div>
    <div class="col-10">
        <h5>'.$lang["PARTNER"]["MENUS"]["MENU_STRUCTURE"].'</h5>    
        <div>
            <div id="list">
        ';
    foreach($MENU->{strtoupper($REQ["menu"])} as $key=>$value) {
        echo "<div class='add-element draggable' data-url='".$key."' data-value='".$value."'><div class='removeItem'><img src='".THEME."/assets/img/48/remove_icon.png'></div>".$value."</div>";
    }
    echo ' 
            </div>
        </div>
        <button class="SaveMenu" data-type="'.$REQ["menu"].'">Save Menu</button>
    </div>
</div>';
}
$load_script[] = "https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js";
echo foot();
