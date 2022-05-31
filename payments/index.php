<?php
include("../base.php");

$transaction_data = $ipp->TransactionsData($REQ["id"]);
echo head();
echo '
        <h2>'.$lang["COMPANY"]["PAYMENT"]["HEADER"].'</h2>
        <div class="row row-cols-md-3 mb-3">
            <div class="col themed-grid-col">'.$lang["COMPANY"]["PAYMENT"]["STATUS"]; echo  $transaction_data->status; echo '</div>
            <div class="col themed-grid-col">'.$lang["COMPANY"]["PAYMENT"]["TIMESTAMP"]; echo  date("Y-m-d H:i:s", $transaction_data->timestamp); echo '</div>
            <div class="col themed-grid-col">'.$lang["COMPANY"]["PAYMENT"]["ACTION"]; echo  $transaction_data->method; echo '</div>
        </div>
        <div class="col-6"></div>
        <div class="row row-cols-md-3 mb-3">
            <div class="col themed-grid-col">'.$lang["COMPANY"]["PAYMENT"]["CARDHOLDER"].'</div>
            <div class="col themed-grid-col">'; echo  $transaction_data->card_data->cardholder; echo '</div>
            <div class="col themed-grid-col">'.$lang["COMPANY"]["PAYMENT"]["AMOUNT"].'</div>
            <div class="col themed-grid-col">'; echo  $transaction_data->amount; echo '</div>
            <div class="col themed-grid-col">Currency</div>
            <div class="col themed-grid-col">'; echo  $currency->currency($transaction_data->currency)[0];
            echo '</div>
        </div>
        <div class="col-6"></div>

        <h2>Acquirer Response</h2>
        <textarea class="form-control" rows="10">'; echo  $transaction_data->acquirer_data->response; echo '</textarea>


        <h2>Related Payments</h2>
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                <tr>
                    <th scope="col">'.$lang["COMPANY"]["PAYMENT"]["FUNCTION"].'</th>
                    <th scope="col">'.$lang["COMPANY"]["PAYMENT"]["TIMESTAMP"].'</th>
                    <th scope="col">'.$lang["COMPANY"]["PAYMENT"]["METHOD"].'</th>
                    <th scope="col">'.$lang["COMPANY"]["PAYMENT"]["CARDHOLDER"].'</th>
                    <th scope="col">'.$lang["COMPANY"]["PAYMENT"]["AMOUNT"].'</th>
                    <th scope="col">'.$lang["COMPANY"]["PAYMENT"]["CURRENCY"].'</th>
                    <th scope="col">'.$lang["COMPANY"]["PAYMENT"]["STATUS"].'</th>
                </tr>
                </thead>
                <tbody>';
                foreach($ipp->TransactionsRelated($transaction_data->transaction_id) as $value) {
                    echo "<tr ";
                    if($value->result == "WAIT") {
                        echo "class='bg-info'";
                    }
                    if($value->result == "NOK") {
                        echo "class='bg-danger'";
                    }
                    echo ">
              <td><a href='/payments/?id=".$value->action_id."' class='btn btn-dark'>".$lang["COMPANY"]["PAYMENT"]["INFO"]."</a></td>
              <td>".date("Y-m-d H:i:s",$value->unixtimestamp)."</td>
              <td>".$value->method."</td>
              <td>".$value->cardholder."</td>
              <td>".number_format($value->amount/100,2,",",".")."</td>
              <td>".$currency->currency($value->currency)[0]."</td>
              <td>".$value->result."</td>
            </tr>";
                }
                echo '
                </tbody>
            </table>
        </div>
';
$load_script[] = "payments.js";

echo foot();
