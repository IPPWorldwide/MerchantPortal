<?php
include("../base.php");

$payment_notifications = $ipp->PaymentNotifications();
echo head();
$actions->get_action("payment_notifications");
echo '
    <div class="row">
        <div class="col-6">
            <h2>'.$lang["PARTNER"]["PAYMENT_NOTIFICATIONS"]["HEADER"].'</h2>
        </div>
    </div>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th scope="col">'.$lang["COMPANY"]["PAYMENT_NOTIFICATIONS"]["ID"].'</th>
              <th scope="col">'.$lang["COMPANY"]["PAYMENT_NOTIFICATIONS"]["TRANSACTION_ID"].'</th>
              <th scope="col">'.$lang["COMPANY"]["PAYMENT_NOTIFICATIONS"]["STATUS"].'</th>
              <th scope="col">'.$lang["COMPANY"]["PAYMENT_NOTIFICATIONS"]["CREATED"].'</th>
              <th scope="col">'.$lang["COMPANY"]["PAYMENT_NOTIFICATIONS"]["LATEST_RETRY"].'</th>
              <th scope="col">'.$lang["COMPANY"]["PAYMENT_NOTIFICATIONS"]["NEXT_RETRY"].'</th>
            </tr>
          </thead>
          <tbody>
          ';
foreach($payment_notifications as $value) {
    echo '
          <tr>
              <td>'.$value->id.'</td>
              <td>'.$value->transaction_id.'</td>
              <td>'.$value->status.'</td>
              <td>'.$value->created->time_readable.'</td>
              <td>'.$value->last->time_readable.'</td>
              <td>'.$value->next->time_readable.'</td>
            </tr>
          <tr>
    ';
}
echo '</tbody>
    </table>
    </div>
';
echo foot();
