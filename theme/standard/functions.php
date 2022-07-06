<?php

function standard_theme_menu($section, $base = "") {
    global $menu, $IPP_CONFIG;
    $html = "";
    foreach($menu->menu($section) as $key=>$value) {
        if($key == "virtual_terminal" && (isset($IPP_CONFIG["PORTAL_DEACTIVATE_VIRTUAL_TERMINAL"]) && $IPP_CONFIG["PORTAL_DEACTIVATE_VIRTUAL_TERMINAL"]))
            continue;
        $html .= "<li class=\"nav-item\">
                <a class=\"nav-link active\" aria-current=\"page\" href=\"/".$base.$key."\">
                    <span data-feather=\"home\"></span>
                    ".$value."
                </a>
            </li>";
    }
    return $html;
}