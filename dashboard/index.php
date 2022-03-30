<?php
include("../base.php");
$payment_type   = $REQ["payment_type"] ?? "ALL";
$result         = $REQ["payment_result"] ?? "ALL";

echo head();
?>
<!doctype html>
      <h2>Transactions</h2>

        <form action="/dashboard" method="GET">
            <div class="form-group">
                <label for="payment_type">Payment Type:</label>
                <select id="payment_type" name="payment_type">
                    <option>ALL</option>
                    <option>AUTH</option>
                    <option>CAPTURE</option>
                    <option>REFUND</option>
                    <option>SECURE</option>
                    <option>CRYPT</option>
                </select>
            </div>
            <div class="form-group">
                <label for="payment_result">Payment Result:</label>
                <select id="payment_result" name="payment_result">
                <option>ALL</option>
                <option>ACK</option>
                <option>NOK</option>
            </select>
            </div>
            <input type="submit" value="Change view" class="btn btn-primary">
        </form>

      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Timestamp</th>
              <th scope="col">Method</th>
              <th scope="col">Cardholder</th>
              <th scope="col">Amount</th>
              <th scope="col">Currency</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <tbody>
          <?php
          foreach($ipp->TransactionsList($payment_type, $result) as $value) {
              echo "<tr ";
              if($value->result == "WAIT") {
                  echo "class='bg-info'";
              }
              if($value->result == "NOK") {
                  echo "class='bg-danger'";
              }
              echo ">
              <td><a href='/payments/?id=".$value->action_id."' class='btn btn-dark'>Info</a></td>
              <td>".date("Y-m-d H:i:s",$value->unixtimestamp)."</td>
              <td>".$value->method."</td>
              <td>".$value->cardholder."</td>
              <td>".number_format($value->amount/100,2,",",".")."</td>
              <td>".$currency->currency($value->currency)[0]."</td>
              <td>".$value->result."</td>
            </tr>";
          }
          ?>
          </tbody>
        </table>
      </div>
<?php
echo foot();