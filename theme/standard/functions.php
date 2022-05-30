<?php

function standard_theme_menu($section, $base = "") {
    global $menu;
    $html = "";
    foreach($menu->menu($section) as $key=>$value) {
        $html .= "<li class=\"nav-item\">
                <a class=\"nav-link active\" aria-current=\"page\" href=\"/".$base.$key."\">
                    <span data-feather=\"home\"></span>
                    ".$value."
                </a>
            </li>";
    }
    return $html;
}