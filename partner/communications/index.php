<?php
include("../b.php");
if(isset($REQ["update"])) {
    $partner->CommunicationTemplateCopyMissing();
    header("Location: /partner/communications");
    die();
}
if(isset($REQ["close"])) {
    $partner->CloseCommuncationTemplate($REQ["template_id"]);
    header("Location: /partner/communications");
    die();
}
$available_communication = [];
if(!is_null($plugins->available_plugins))
    foreach($plugins->available_plugins as $value) {
        if(isset($value->communication->type) && !in_array($value->communication->type,$available_communication))
            $available_communication[] = $value->communication->type;
    }
$templates = $partner->ListTemplates();
echo head();
echo '
    <div class="row">
        <div class="col-6">
            <h2>'.$lang["PARTNER"]["OUTBOUND_COMMUNICATION"]["HEADER"].'</h2>
        </div>
        <div class="col-6 text-end">
            <a class="btn btn-success" href="?update=all">'.$lang["PARTNER"]["OUTBOUND_COMMUNICATION"]["SYNC"].'</a>
            <a class="btn btn-success" href="data.php">'.$lang["PARTNER"]["OUTBOUND_COMMUNICATION"]["ADD_NEW"].'</a>
            <a class="btn btn-success" href="communication.php">'.$lang["PARTNER"]["OUTBOUND_COMMUNICATION"]["SENT_OUT"].'</a>
        </div>
    </div>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th scope="col">'.$lang["PARTNER"]["OUTBOUND_COMMUNICATION"]["HOOK"].'</th>
              <th scope="col">'.$lang["PARTNER"]["OUTBOUND_COMMUNICATION"]["TYPE"].'</th>
              <th scope="col">'.$lang["PARTNER"]["OUTBOUND_COMMUNICATION"]["TITLE"].'</th>
              <th scope="col">'.$lang["PARTNER"]["OUTBOUND_COMMUNICATION"]["ACTIVE"].'</th>
              <th scope="col">'.$lang["PARTNER"]["OUTBOUND_COMMUNICATION"]["FUNCTION"].'</th>
            </tr>
          </thead>
          <tbody>
          <tr>';
          foreach($templates as $value) {
              echo "
              <td>".$value->hook."</td>
              ";
              echo "<td>";
              if(is_null($available_communication) || (isset($available_communication) && !in_array($value->type,$available_communication))) {
                  echo ICON_INFO . " " .  $value->type . " " . $lang["PARTNER"]["OUTBOUND_COMMUNICATION"]["MISSING_PLUGIN"] . "<br />";
              }
              echo $value->type;
              echo "</td>";
              echo "
              <td>".$value->title."</td>
              <td>".$value->active."</td>
              <td>
                <a href='data.php?template_id=".$value->id."' class='btn btn-info'>".$lang["PARTNER"]["OUTBOUND_COMMUNICATION"]["EDIT"]."</a>
                <a href='?close=1&template_id=".$value->id."' class='btn btn-warning'>".$lang["PARTNER"]["OUTBOUND_COMMUNICATION"]["CLOSE"]."</a>
              </td>
            </tr>";
          }
          ?>
          </tbody>
        </table>
      </div>

<?php

echo foot();