<?php
include("../../b.php");

$subscription_plans = $partner->ListSubscriptionPlans();
echo head();
echo '
    <div class="row">
        <div class="col-6">
            <h2>'.$lang["PARTNER"]["INVOICES_PLAN"]["HEADER"].'</h2>
        </div>
        <div class="col-6 text-end">
            <a class="btn btn-success" href="add.php">'.$lang["PARTNER"]["INVOICES_PLAN"]["ADD_NEW"].'</a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th scope="col">'.$lang["PARTNER"]["INVOICES_PLAN"]["ID"].'</th>
                <th scope="col">'.$lang["PARTNER"]["INVOICES_PLAN"]["NAME"].'</th>
                <th scope="col">'.$lang["PARTNER"]["INVOICES_PLAN"]["CURRENCY"].'</th>
                <th scope="col">'.$lang["PARTNER"]["INVOICES_PLAN"]["MONTHLY_FEE"].'</th>
                <th scope="col">'.$lang["PARTNER"]["INVOICES_PLAN"]["TNX_FEE"].'</th>
                <th scope="col">'.$lang["PARTNER"]["INVOICES_PLAN"]["STATUS"].'</th>
            </tr>
            </thead>
            <tbody>
            <tr>';
            foreach($subscription_plans as $value) {
                echo "
              <td>".$value->id."</td>
              <td>".$value->name."</td>
              <td>".$value->currency_txt."</td>
              <td>".$value->amount_readable."</td>
              <td>".$value->amount_tnx_readable."</td>
              <td><a href='/partner/invoices/plans/details.php?id=".$value->id."' class='btn btn-dark'>".$lang["PARTNER"]["INVOICES_PLAN"]["INFO"]."</a></td>
            </tr>";
            }
            echo '
            </tbody>
        </table>
    </div>
';
echo foot();