<?php
include("../base.php");

echo head();
?>
      <h2>Payouts</h2>
    <table class="table table-striped table-sm">
        <thead>
        <tr>
            <th scope="col">Date</th>
            <th scope="col">Gross Volume</th>
            <th scope="col">Calculated Fees</th>
            <th scope="col">Settlement</th>
            <th scope="col">Settled</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($ipp->ListPayouts() as $value) {
            echo "<tr class='align-middle'>";
                echo "<td><a href='/payouts/data.php?id=".$value->id."' class='btn btn-dark'>Info</a></td>";
                echo "<td>".$value->amount->gross->amount_readable."</td>";
                echo "<td>".$value->amount->fee->amount_readable."</td>";
                echo "<td>".$value->amount->expected_settlement->amount_readable."</td>";
                echo "<td>".$value->settled."</td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
<?php
echo foot();