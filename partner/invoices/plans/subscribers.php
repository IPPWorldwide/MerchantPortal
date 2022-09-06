<?php
include("../../b.php");

$subscription_plans = $partner->ListSubscriptionPlansSubscribers($REQ["id"]);
echo head();
echo '
    <div class="row">
        <div class="col-6">
            <h2>'.$lang["PARTNER"]["INVOICES_PLAN_SUBSCRIBERS"]["HEADER"].'</h2>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th scope="col">'.$lang["PARTNER"]["INVOICES_PLAN"]["ID"].'</th>
                <th scope="col">'.$lang["PARTNER"]["INVOICES_PLAN_SUBSCRIBERS"]["COMPANY_ID"].'</th>
                <th scope="col">'.$lang["PARTNER"]["INVOICES_PLAN_SUBSCRIBERS"]["COMPANY_NAME"].'</th>
                <th scope="col">'.$lang["PARTNER"]["INVOICES_PLAN_SUBSCRIBERS"]["NEXT_INVOICE"].'</th>
                <th scope="col">'.$lang["PARTNER"]["INVOICES_PLAN_SUBSCRIBERS"]["STATUS"].'</th>
            </tr>
            </thead>
            <tbody>
            <tr>';
            foreach($subscription_plans as $value) {
                echo "
              <td>".$value->id."</td>
              <td>".$value->company_id."</td>
              <td>".$value->name."</td>
              <td>".$value->next->readable."</td>
              <td><a href='/partner/companies/details.php?id=".$value->company_id."' class='btn btn-dark'>".$lang["PARTNER"]["INVOICES_PLAN"]["INFO"]."</a></td>
            </tr>";
            }
            echo '
            </tbody>
        </table>
    </div>
';
echo foot();