<?php
include("../base.php");
$state   = $REQ["dispute_state"] ?? "ALL";
$status         = $REQ["dispute_status"] ?? "ALL";

echo head();
?>
    <!doctype html>
    <h2>Disputes</h2>
    <form action="/disputes" method="GET">
        <div class="form-group">
            <label for="payment_type">Payment Type:</label>
            <select id="dispute_status" name="dispute_status">
                <option>OPEN</option>
                <option>CLOSED</option>
            </select>
        </div>
        <div class="form-group">
            <label for="payment_result">Payment Result:</label>
            <select id="dispute_state" name="dispute_state">
                <option>Received</option>
                <option>Represented</option>
                <option>1st Chargeback</option>
                <option>2nd Chargeback</option>
                <option>Lost</option>
            </select>
        </div>
        <input type="submit" value="Change view" class="btn btn-primary">
    </form>

    <table class="table table-striped table-sm">
        <thead>
        <tr>
            <th scope="col">Dispute ID</th>
            <th scope="col">Transaction ID</th>
            <th scope="col">Disputed Amount</th>
            <th scope="col">Order Amount</th>
            <th scope="col">Currency</th>
            <th scope="col">Status</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($ipp->ListDisputes($state, $status) as $value) {
            echo "<tr>";
            echo "<td><a href='/disputes/?id=".$value->id."' class='btn btn-dark'>Info</a></td>";
            echo "<td>".$value->transaction->id."</td>";
            echo "<td>".$value->amount_readable."</td>";
            echo "<td>".$value->transaction->amount_readable."</td>";
            echo "<td>".$value->transaction->currency->id."</td>";
            echo "<td>".$value->status."</td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
    </div>
<?php
echo foot();