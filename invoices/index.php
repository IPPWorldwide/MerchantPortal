<?php
include("../base.php");

$invoices = $ipp->Listinvoices();

echo head();
?>
    <div class="row">
        <div class="col-6">
            <h2>Issued Invoices</h2>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Amount</th>
                <th scope="col">VAT</th>
                <th scope="col">Period End</th>
                <th scope="col">Paid</th>
                <th scope="col">#</th>
            </tr>
            </thead>
            <tbody>
            <?php
            echo "<tr>";
            foreach($invoices as $value) {
                echo "
              <td>".$value->id."</td>
              <td>".$value->amount_readable." ".$value->currency_txt."</td>
              <td>".$value->vat."%</td>
              <td>".$value->readable_end."</td>
              <td>";
                if($value->cancelled == 1)
                    echo "Cancelled";
                elseif($value->paid == 1)
                    echo "Paid";
                else
                    echo "Unpaid";
              echo "</td>
              <td><a class=\"btn btn-info\" href=\"show.php?id=".$value->id."\">Show</a></td>
            </tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
<?php

echo foot();