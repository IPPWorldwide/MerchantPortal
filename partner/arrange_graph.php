<?php
if($_SERVER['REQUEST_METHOD'] == "POST"){
    include("b.php");

    include("../controller/IPPConfig.php");
    $config = new IPPConfig();
    $new_config = $config->UpdateConfig("GRAPH_SETTING",json_encode($_POST['graph_sequence']));
    $config = $config->WriteConfig();
}