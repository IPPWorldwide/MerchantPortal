<?php
include("../base.php");

$invoices = $ipp->Listinvoices();

echo head();
echo '
    <div class="row">
        <div class="col-6">
            <h2>'.$lang["COMPANY"]["INVOICES"]["HEADER"].'</h2>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th scope="col">'.$lang["COMPANY"]["INVOICES"]["ID"].'</th>
                <th scope="col">'.$lang["COMPANY"]["INVOICES"]["AMOUNT"].'</th>
                <th scope="col">'.$lang["COMPANY"]["INVOICES"]["VAT"].'</th>
                <th scope="col">'.$lang["COMPANY"]["INVOICES"]["PERIOD_END"].'</th>
                <th scope="col">'.$lang["COMPANY"]["INVOICES"]["PAID"].'</th>
                <th scope="col">'.$lang["COMPANY"]["INVOICES"]["FUNCTION"].'</th>
            </tr>
            </thead>
            <tbody>
            ';
            echo "<tr>";
            foreach($invoices as $value) {
                echo "
              <td>".$value->id."</td>
              <td>".$value->amount_readable." ".$value->currency_txt."</td>
              <td>".$value->vat."%</td>
              <td>".$value->readable_end."</td>
              <td>";
                if($value->cancelled == 1)
                    echo $lang["COMPANY"]["INVOICES"]["CANCELLED"];
                elseif($value->paid == 1)
                    echo $lang["COMPANY"]["INVOICES"]["PAID_TEXT"];
                else
                    echo $lang["COMPANY"]["INVOICES"]["UNPAID"];
              echo "</td>
              <td><a class=\"btn btn-info\" href=\"show.php?id=".$value->id."\">".$lang["COMPANY"]["INVOICES"]["SHOW"]."</a></td>
            </tr>";
            }
            echo '
            </tbody>
        </table>
    </div>
';
echo foot();