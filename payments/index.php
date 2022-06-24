<?php
include("../base.php");

if(isset($REQ["action"])) {
    var_dump($ipp->TransactionsAction($REQ["action"],$REQ["id"],$REQ["action_id"],$REQ["amount"] ?? 0));
    die();
}

$transaction_data = $ipp->TransactionsData($REQ["id"]);
echo head();
echo '
<div class="row row-cols-md-2 mb-2">
    <div class="col-6">
        <h2>'.$lang["COMPANY"]["PAYMENT"]["HEADER"].'</h2>
        <div class="row row-cols-md-2 mb-2">
                <div class="col themed-grid-col">'.$lang["COMPANY"]["PAYMENT"]["TRANSACTION_ID"].'</div>
                <div class="col themed-grid-col">'; echo  $transaction_data->transaction_id; echo '</div>
        </div>
        <div class="row row-cols-md-2 mb-2">
            <div class="col themed-grid-col">'.$lang["COMPANY"]["PAYMENT"]["TOP_STATUS"].'</div>
            <div class="col themed-grid-col">'; echo  $transaction_data->status; echo '</div>  
        </div>
        <div class="row row-cols-md-2 mb-2">
            <div class="col themed-grid-col">'.$lang["COMPANY"]["PAYMENT"]["TIMESTAMP"].'</div>
            <div class="col themed-grid-col">'; echo  date("Y-m-d H:i:s", $transaction_data->timestamp); echo '</div>
        </div>
        <div class="row row-cols-md-2 mb-2">
            <div class="col themed-grid-col">'.$lang["COMPANY"]["PAYMENT"]["CARDHOLDER"].'</div>
            <div class="col themed-grid-col">'; echo  $transaction_data->card_data->cardholder; echo '</div>
        </div>
        <div class="row row-cols-md-2 mb-2">
            <div class="col themed-grid-col">'.$lang["COMPANY"]["PAYMENT"]["AMOUNT"].'</div>
            <div class="col themed-grid-col">'; echo  $transaction_data->amount; echo '</div>
        </div>
        <div class="row row-cols-md-2 mb-2">
            <div class="col themed-grid-col">'.$lang["COMPANY"]["PAYMENT"]["CURRENCY"].'</div>
            <div class="col themed-grid-col">'; echo  $currency->currency($transaction_data->currency)[0].'</div>
        </div>
        ';
        if($transaction_data->method == "AUTH") {
            echo '
                <div class="row row-cols-md-2 mb-2">
                    <div class="col themed-grid-col"><input type="number" id="amountCapture" class="form-control" value="'; echo  $transaction_data->amount; echo '"></div>
                    <div class="col themed-grid-col"><button data-type="Capture" class="btnCapture btnAction btn btn-success">'.$lang["COMPANY"]["PAYMENT"]["CAPTURE"].'</button></div>
                </div>
                <div class="row row-cols-md-2 mb-2">
                    <div class="col themed-grid-col"><input type="number" id="amountIncremental" class="form-control" value="'; echo  $transaction_data->amount; echo '"></div>
                    <div class="col themed-grid-col"><button data-type="Incremental" class="btnIncremental btnAction btn btn-success">'.$lang["COMPANY"]["PAYMENT"]["INCREMENTAL_AUTH"].'</button></div>
                </div>
            ';
        }
        if(($transaction_data->method === "CAPTURE") || ($transaction_data->method === "SALE")) {
            if(!isset($IPP_CONFIG["PORTAL_LOCAL_DEACTIVATE_REFUNDS"]) || ($IPP_CONFIG["PORTAL_LOCAL_DEACTIVATE_REFUNDS"] !== "1")) {
                echo '
                <div class="row row-cols-md-2 mb-2">
                    <div class="col themed-grid-col"><input type="number" id="amountRefund" class="form-control" value="'; echo  $transaction_data->amount; echo '"></div>
                    <div class="col themed-grid-col"><button data-type="Refund" class="btnRefund btnAction btn btn-warning">'.$lang["COMPANY"]["PAYMENT"]["REFUND"].'</button></div>
                </div>        
            ';
            }
        }
        if(!isset($IPP_CONFIG["PORTAL_LOCAL_DEACTIVATE_VOID"]) || ($IPP_CONFIG["PORTAL_LOCAL_DEACTIVATE_VOID"] !== "1")) {
            echo '<div class="row row-cols-md-2 mb-2">
            <div class="col themed-grid-col"><button data-type="Void" class="btnAction btnVoid btn btn-warning">'.$lang["COMPANY"]["PAYMENT"]["VOID"].'</button></div>
        </div>';
        }
        echo '
    </div>
    <div class="col-6 related_payments">
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
        <h2>Acquirer Response</h2>
        <textarea class="form-control" rows="3">'; echo  $transaction_data->acquirer_data->response; echo '</textarea>
    </div>
</div>        
';
$inline_script[] = "var payment_id='".$transaction_data->transaction_id."';";
$inline_script[] = "var action_id='".$REQ["id"]."';";
echo foot();
