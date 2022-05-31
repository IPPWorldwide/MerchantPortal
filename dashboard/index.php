<?php
include("../base.php");
$payment_type   = $REQ["payment_type"] ?? "ALL";
$result         = $REQ["payment_result"] ?? "ALL";

echo head();
echo '
      <h2>'.$lang["COMPANY"]["DASHBOARD"]["HEADER"].'</h2>
        <form action="/dashboard" method="GET">
            <div class="form-group">
                <label for="payment_type">'.$lang["COMPANY"]["DASHBOARD"]["PAYMENT_TYPE"].'</label>
                <select id="payment_type" name="payment_type">
                    <option value="ALL">'.$lang["COMPANY"]["DASHBOARD"]["ALL"].'</option>
                    <option value="AUTH">'.$lang["COMPANY"]["DASHBOARD"]["AUTH"].'</option>
                    <option value="CAPTURE">'.$lang["COMPANY"]["DASHBOARD"]["CAPTURE"].'</option>
                    <option value="REFUND">'.$lang["COMPANY"]["DASHBOARD"]["REFUND"].'</option>
                    <option value="SECURE">'.$lang["COMPANY"]["DASHBOARD"]["SECURE"].'</option>
                    <option value="CRYPT">'.$lang["COMPANY"]["DASHBOARD"]["CRYPT"].'</option>
                </select>
            </div>
            <div class="form-group">
                <label for="payment_result">'.$lang["COMPANY"]["DASHBOARD"]["PAYMENT_RESULT"].'</label>
                <select id="payment_result" name="payment_result">
                <option value="ALL">'.$lang["COMPANY"]["DASHBOARD"]["ALL"].'</option>
                <option value="ACK">'.$lang["COMPANY"]["DASHBOARD"]["ACK"].'</option>
                <option value="NOK">'.$lang["COMPANY"]["DASHBOARD"]["NOK"].'</option>
            </select>
            </div>
            <input type="submit" value="'.$lang["COMPANY"]["DASHBOARD"]["CHANGE_VIEW"].'" class="btn btn-primary">
        </form>

      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th scope="col">'.$lang["COMPANY"]["DASHBOARD"]["FUNCTION"].'</th>
              <th scope="col">'.$lang["COMPANY"]["DASHBOARD"]["TIMESTAMP"].'</th>
              <th scope="col">'.$lang["COMPANY"]["DASHBOARD"]["METHOD"].'</th>
              <th scope="col">'.$lang["COMPANY"]["DASHBOARD"]["CARDHOLDER"].'</th>
              <th scope="col">'.$lang["COMPANY"]["DASHBOARD"]["AMOUNT"].'</th>
              <th scope="col">'.$lang["COMPANY"]["DASHBOARD"]["CURRENCY"].'</th>
              <th scope="col">'.$lang["COMPANY"]["DASHBOARD"]["STATUS"].'</th>
            </tr>
          </thead>
          <tbody>
          ';
foreach($ipp->TransactionsList($payment_type, $result) as $value) {
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
            </tr>";
          }
echo '
          </tbody>
        </table>
      </div>
';
echo foot();