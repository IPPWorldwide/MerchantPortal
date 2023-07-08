<?php

include("b.php");
echo $partner_graph->GenerateView($REQ["type"],$REQ["source"],$partner_graph->getDataSource($REQ["data"])["length"],$REQ["source"]);
