<?php
include("../b.php");

$list_scheme_accounts = $partner->ListKickback();
echo head();
$actions->get_action("partner_kickback");

echo '
    <div class="row row-cols-md-2 mb-2">
        <div class="col themed-grid-col">
            <div class="col-6">
                <h2>'.$lang["PARTNER"]["KICKBACK"]["HEADER"].'</h2>
            </div>
        </div>
        <div class="col themed-grid-col d-flex flex-row-reverse">
            <input type="button" class="btn btn-secondary " onclick="array2excel()" value="'.$lang["COMPANY"]["EXPORT_TABLE"].'" />
        </div>
    </div>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th scope="col">'.$lang["PARTNER"]["KICKBACK"]["TRANSACTION_ID"].'</th>
              <th scope="col">'.$lang["PARTNER"]["KICKBACK"]["ORDER_ID"].'</th>
              <th scope="col">'.$lang["PARTNER"]["KICKBACK"]["COMPANY_ID"].'</th>
              <th scope="col">'.$lang["PARTNER"]["KICKBACK"]["COMPANY_NAME"].'</th>
              <th scope="col">'.$lang["PARTNER"]["KICKBACK"]["ACTION_ID"].'</th>
              <th scope="col">'.$lang["PARTNER"]["KICKBACK"]["TRANSACTION_DATE"].'</th>
              <th scope="col">'.$lang["PARTNER"]["KICKBACK"]["CURRENCY"].'</th>
              <th scope="col">'.$lang["PARTNER"]["KICKBACK"]["ACTION_VALUE"].'</th>
              <th scope="col">'.$lang["PARTNER"]["KICKBACK"]["COST"].'</th>
              <th scope="col">'.$lang["PARTNER"]["KICKBACK"]["PROFIT_CURRENCY"].'</th>
              <th scope="col">'.$lang["PARTNER"]["KICKBACK"]["PROFIT"].'</th>
              <th scope="col">'.$lang["PARTNER"]["KICKBACK"]["SETTLED"].'</th>
              <th scope="col">'.$lang["PARTNER"]["KICKBACK"]["SETTLED_DATE"].'</th>
            </tr>
          </thead>
          <tbody>';
            foreach($list_scheme_accounts as $value) {
                foreach($value->share->transactions as $tnx) {
                    echo '       
                    <tr>
                      <td>'.$tnx->id.'</td>
                      <td>'.$tnx->order_data->id.'</td>
                      <td>'.$tnx->company->id.'</td>
                      <td>'.$tnx->company->name.'</td>
                      <td>'.$tnx->action->id.'</td>
                      <td>'.$tnx->action->time->readable.'</td>
                      <td>'.$tnx->currency->txt.'</td>
                      <td>'.$tnx->action->value.'</td>
                      <td>'.$tnx->costs->total.'</td>
                      <td>'.$tnx->profit->currency->txt.'</td>
                      <td>'.$tnx->profit->value.'</td>
                      <td>'.$tnx->settled.'</td>
                      <td>'.$tnx->settled_date.'</td>
                    </tr>
                    ';
                }
            }
          ?>
          </tbody>
        </table>
      </div>
<?php
echo foot();
