<?php
include("../base.php");
header('Content-Type: application/json; charset=utf-8');

echo $REQ["callback"];
$search = $ipp->Search($REQ["q"]);
$i = 1;
$total = count((array)$search);
echo "([";
foreach($search as $key=>$value) {
    echo "{action_id:\"" . $value->action_id."\",cardno:\"".$value->card->hashed."\",holder:\"" . $value->card->holder."\",amount:\"" . $value->amount->readable."\",currency:\"" . $value->currency->text."\",date:\"".$value->dates->created->readable."\"}";
    if($i!==$total)
        echo ",";
    $i++;
}
echo "]);";