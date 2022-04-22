<?php
include("../base.php");
$result         = $REQ["result"] ?? "ALL";
echo head();
?>
<!doctype html>
      <h2>Subscriptions</h2>

        <form action="/subscriptions" method="GET">
            <div class="form-group">
                <label for="result">Subscription Result:</label>
                <select id="result" name="result">
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
              <th scope="col">Transaction ID</th>
              <th scope="col">Card holder</th>
              <th scope="col">Card hashed</th>
              <th scope="col">Expmonth / Year</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <tbody>
          <?php
          foreach($ipp->SubscriptionsList($result) as $value) {
              echo "<tr>
              <td>".$value->transaction_id."</td>
              <td>".$value->card->holder."</td>
              <td>".$value->card->hashed."</td>
              <td>".$value->card->expmonth." ".$value->card->expyear."</td>
              <td>".$value->result."</td>
            </tr>";
          }
          ?>
          </tbody>
        </table>
      </div>
<?php
echo foot();