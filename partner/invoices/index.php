<?php
include("../b.php");

$invoices = $partner->Listinvoices();

echo head();
echo '
    <div class="row">
        <div class="col-6">
            <h2>'.$lang["PARTNER"]["INVOICES"]["HEADER"].'</h2>
        </div>
        <div class="col-6 text-end">
            <a class="btn btn-success" href="add.php">'.$lang["PARTNER"]["INVOICES"]["ADD_NEW"].'</a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th scope="col">'.$lang["PARTNER"]["INVOICES"]["ID"].'</th>
                <th scope="col">'.$lang["PARTNER"]["INVOICES"]["COMPANY"].'</th>
                <th scope="col">'.$lang["PARTNER"]["INVOICES"]["PACKAGE"].'</th>
                <th scope="col">'.$lang["PARTNER"]["INVOICES"]["AMOUNT"].'</th>
                <th scope="col">'.$lang["PARTNER"]["INVOICES"]["VAT"].'</th>
                <th scope="col">'.$lang["PARTNER"]["INVOICES"]["PERIOD_END"].'</th>
                <th scope="col">'.$lang["PARTNER"]["INVOICES"]["PAID"].'</th>
                <th scope="col">'.$lang["PARTNER"]["INVOICES"]["FUNCTIONS"].'</th>
            </tr>
            </thead>
            <tbody>
            <tr>';
            foreach($invoices as $value) {
                echo "
              <td>".$value->id."</td>
              <td>".$value->company_id."</td>
              <td>".$value->package_id."</td>
              <td>".$value->amount_readable." ".$value->currency_txt."</td>
              <td>".$value->vat."%</td>
              <td>".date("Y-m-d", $value->period_end)."</td>
              <td>";
                if($value->cancelled == 1)
                    echo $lang["PARTNER"]["INVOICES"]["CANCELLED"];
                elseif($value->paid == 1)
                    echo $lang["PARTNER"]["INVOICES"]["PAID_TXT"];
                else
                    echo $lang["PARTNER"]["INVOICES"]["UNPAID"];
              echo "</td>
              <td><a class=\"btn btn-info\" href=\"show.php?id=".$value->id."\">".$lang["PARTNER"]["INVOICES"]["SHOW"]."</a></td>
            </tr>";
            }
            echo '
            </tbody>
        </table>
    </div>

';
echo foot();