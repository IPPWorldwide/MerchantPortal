<?php
include("../base.php");

$payment_type   = $REQ["payment_type"] ?? "AUTH+SALE";
$result         = $REQ["payment_result"] ?? "ALL";
$payment_start  = $REQ["payment_start"] ?? date("Y-m-d\TH:i", (time()-86400*30));
$payment_end    = $REQ["payment_end"] ?? date("Y-m-d\TH:i", (time()+600));

if(isset($REQ["userid"])) {
    $ipp->ResetUserPassword($_COOKIE["ipp_user_id"],$REQ["password"]);
    die();
}
if($company_data->content->user->password->new) {
    $inline_script[] = "$( document ).ready(function() { $('#passwordModal').modal('show'); });     $('#passwordModal .modal-title').text('New password');
    $('#passwordModal #user-id').val('" . $_COOKIE["ipp_user_id"] .  "');";
}
echo head();
$actions->get_action("dashboard");
echo '
      <h2>'.$lang["COMPANY"]["DASHBOARD"]["HEADER"].'</h2>
        <div class="row row-cols-md-2 mb-2">
            <div class="col themed-grid-col">
                <form action="/dashboard" method="GET">
                    <table>
                        <tr>
                            <td>'.$lang["COMPANY"]["DASHBOARD"]["PAYMENT_TYPE"].'</td>
                            <td><select id="payment_type" name="payment_type">
                                    <option value="ALL"'; if($payment_type === "ALL") { echo " selected"; } echo '>'.$lang["COMPANY"]["DASHBOARD"]["ALL"].'</option>
                                    <option value="AUTH"'; if($payment_type === "AUTH") { echo " selected"; } echo '>'.$lang["COMPANY"]["DASHBOARD"]["AUTH"].'</option>
                                    <option value="SALE"'; if($payment_type === "SALE") { echo " selected"; } echo '>'.$lang["COMPANY"]["DASHBOARD"]["SALE"].'</option>
                                    <option value="AUTH+SALE"'; if($payment_type === "AUTH+SALE") { echo " selected"; } echo '>'.$lang["COMPANY"]["DASHBOARD"]["AUTHANDSALE"].'</option>
                                    <option value="CAPTURE"'; if($payment_type === "CAPTURE") { echo " selected"; } echo '>'.$lang["COMPANY"]["DASHBOARD"]["CAPTURE"].'</option>
                                    <option value="REFUND"'; if($payment_type === "REFUND") { echo " selected"; } echo '>'.$lang["COMPANY"]["DASHBOARD"]["REFUND"].'</option>
                                    <option value="CREDIT"'; if($payment_type === "CREDIT") { echo " selected"; } echo '>'.$lang["COMPANY"]["DASHBOARD"]["CREDIT"].'</option>
                                    <option value="SECURE"'; if($payment_type === "SECURE") { echo " selected"; } echo '>'.$lang["COMPANY"]["DASHBOARD"]["SECURE"].'</option>
                                    <option value="CRYPT"'; if($payment_type === "CRYPT") { echo " selected"; } echo '>'.$lang["COMPANY"]["DASHBOARD"]["CRYPT"].'</option>
                                </select></td>        
                        </tr>
                        <tr>
                            <td>'.$lang["COMPANY"]["DASHBOARD"]["PAYMENT_RESULT"].'</td>
                            <td><select id="payment_result" name="payment_result">
                                <option value="ALL"'; if($result === "ALL") { echo " selected"; } echo '>'.$lang["COMPANY"]["DASHBOARD"]["ALL"].'</option>
                                <option value="ACK"'; if($result === "ACK") { echo " selected"; } echo '>'.$lang["COMPANY"]["DASHBOARD"]["ACK"].'</option>
                                <option value="NOK"'; if($result === "NOK") { echo " selected"; } echo '>'.$lang["COMPANY"]["DASHBOARD"]["NOK"].'</option>
                            </select></td>
                        </tr>
                        <tr>
                            <td>'.$lang["COMPANY"]["DASHBOARD"]["STARTING_DATE"].'</td>
                            <td><input type="datetime-local" id="payment_start" name="payment_start" value="'.$payment_start.'"></td>
                        </tr>
                        <tr>
                            <td>'.$lang["COMPANY"]["DASHBOARD"]["ENDING_DATE"].'</td>
                            <td><input type="datetime-local" id="payment_end" name="payment_end" value="'.$payment_end.'"></td>
                        </tr>
                    </table>
                    <input type="submit" value="'.$lang["COMPANY"]["DASHBOARD"]["CHANGE_VIEW"].'" class="btn btn-primary">
                </form>
            </div>
            <div class="col themed-grid-col d-flex flex-row-reverse">
                <input type="button" class="btn btn-secondary " onclick="array2excel()" value="'.$lang["COMPANY"]["EXPORT_TABLE"].'" />
            </div>
        </div>
      <div class="table-responsive">
        <table class="table table-striped table-sm" id="tnx_list">
          <thead>
            <tr>
              <th scope="col">'.$lang["COMPANY"]["DASHBOARD"]["FUNCTION"].'</th>
              <th scope="col">'.$lang["COMPANY"]["DASHBOARD"]["TIMESTAMP"].'</th>
              <th scope="col">'.$lang["COMPANY"]["DASHBOARD"]["METHOD"].'</th>
              <th scope="col">'.$lang["COMPANY"]["DASHBOARD"]["CARDHOLDER"].'</th>
              <th scope="col">'.$lang["COMPANY"]["DASHBOARD"]["AMOUNT"].'</th>
              <th scope="col">'.$lang["COMPANY"]["DASHBOARD"]["CURRENCY"].'</th>
              <th scope="col">'.$lang["COMPANY"]["DASHBOARD"]["STATUS"].'</th>
              <th scope="col">'.$lang["COMPANY"]["DASHBOARD"]["REASON"].'</th>
            </tr>
          </thead>
          <tbody>
          ';
$transaction_list = $ipp->TransactionsList($payment_type, $result,$payment_start,$payment_end);
if(is_array($transaction_list))
    foreach($transaction_list as $value) {
              echo "<tr ";
              if($value->result == "WAIT") {
                  echo "class='bg-info'";
              }
              if($value->result == "NOK") {
                  echo "class='bg-danger'";
              }
              echo ">
              <td><a href='/payments/?id=".$value->action_id."' class='btn btn-dark'>".$lang["COMPANY"]["DASHBOARD"]["INFO"]."</a></td>
              <td>".date("Y-m-d H:i:s",$value->unixtimestamp)."</td>
              <td>".$value->method."</td>
              <td>".$value->cardholder."</td>
              <td>".number_format($value->amount/100,2,",",".")."</td>
              <td>".$currency->currency($value->currency)[0]."</td>
              <td>".$value->result."</td>
              <td>".$value->reason."</td>              
            </tr>";
          }
echo '
          </tbody>
        </table>
      </div>
    <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" name="user_id" id="user-id" readonly>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">'.$lang["COMPANY"]["USERS"]["PASSWORD"].'</label>
                            <input type="password" class="form-control checkPasswordUser" autocomplete="new-password" name="password" id="password">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">'.$lang["COMPANY"]["USERS"]["REPEAT_PASSWORD"].'</label>
                            <input type="password" class="form-control checkPasswordUser" autocomplete="new-password" id="repeat-password">
                            <small id="PasswordRequirements">'.$lang["COMPANY"]["USERS"]["PASSWORD_REQUIREMENTS"].'</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeModal">'.$lang["COMPANY"]["USERS"]["CLOSE"].'</button>
                    <button type="button" class="btn btn-primary confirm" disabled>'.$lang["COMPANY"]["USERS"]["CHANGE_PASSWORD"].'</button>
                </div>
            </div>
        </div>
    </div>
';
echo foot();
