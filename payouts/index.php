<?php
include("../base.php");

echo head();
echo '
      <h2>'.$lang["PARTNER"]["PAYOUTS"]["HEADER"].'</h2>
    <table class="table table-striped table-sm">
        <thead>
        <tr>
            <th scope="col">'.$lang["PARTNER"]["PAYOUTS"]["DATE"].'</th>
            <th scope="col">'.$lang["PARTNER"]["PAYOUTS"]["GROSS_VOLUME"].'</th>
            <th scope="col">'.$lang["PARTNER"]["PAYOUTS"]["CALCULATED_FEE"].'</th>
            <th scope="col">'.$lang["PARTNER"]["PAYOUTS"]["SETTLEMENT"].'</th>
            <th scope="col">'.$lang["PARTNER"]["PAYOUTS"]["SETTLED"].'</th>
        </tr>
        </thead>
        <tbody>
';
        foreach($ipp->ListPayouts() as $value) {
            echo "<tr class='align-middle'>";
                echo "<td><a href='/payouts/data.php?id=".$value->id."' class='btn btn-dark'>".$lang["PARTNER"]["PAYOUTS"]["INFO"]."</a></td>";
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