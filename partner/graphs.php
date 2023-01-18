<?php

include("b.php");
echo $partner_graph->GenerateView($REQ["type"],$partner_graph->getDataSource($REQ["data"])["source"],$partner_graph->getDataSource($REQ["data"])["length"]);
