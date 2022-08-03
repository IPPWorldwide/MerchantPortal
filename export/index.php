<?php
include("../base.php");

header('Content-Type: application/xls');
header('Content-Disposition: attachment; filename=info.xls');

$data = json_decode($_POST['array2excel'], false);
\Shuchkin\SimpleXLSXGen::fromArray($data)->downloadAs('export.xlsx');
