<?php
include("../b.php");
echo head();
echo '
      <h2>'.$lang["PARTNER"]["OUTBOUND_SENT"]["HEADER"].'</h2>
      <div class="table-responsive">
        <table class="table table-striped table-sm" id="tnx_list">
          <thead>
            <tr>
              <th scope="col">'.$lang["PARTNER"]["OUTBOUND_SENT"]["FUNCTION"].'</th>
              <th scope="col">'.$lang["PARTNER"]["OUTBOUND_SENT"]["TIMESTAMP"].'</th>
              <th scope="col">'.$lang["PARTNER"]["OUTBOUND_SENT"]["RECEIVER"].'</th>
              <th scope="col">'.$lang["PARTNER"]["OUTBOUND_SENT"]["TITLE"].'</th>
              <th scope="col">'.$lang["PARTNER"]["OUTBOUND_SENT"]["MESSAGE"].'</th>
              <th scope="col">'.$lang["PARTNER"]["OUTBOUND_SENT"]["ATTACHMENTS"].'</th>
              <th scope="col">'.$lang["PARTNER"]["OUTBOUND_SENT"]["STATUS"].'</th>
            </tr>
          </thead>
          <tbody>
          ';
$outbound_list = $partner->ListOutboundCommunication();
if(is_object($outbound_list))
    foreach($outbound_list as $value) {
              echo "<tr ";

              echo ">
              <td></td>
              <td>".date("Y-m-d H:i:s",$value->status->time)."</td>
              <td>".$value->receiver->id."</td>
              <td>".$value->data->title."</td>
              <td>".substr($value->data->message, 0, 75); if(strlen($value->data->message) > 75){ echo '...'; }; echo "</td>
              <td>".$value->attachments->name."</td>
              <td>";
        if($value->status->failed === "1")
            echo "Failed";
        elseif($value->status->failed === "0" && $value->status->finished === "1")
            echo "Finished";
        elseif($value->status->failed === "0" && $value->status->finished === "0")
            echo "Pending";

              echo "</td>
            </tr>";
          }
echo '
          </tbody>
        </table>
      </div>
';
echo foot();