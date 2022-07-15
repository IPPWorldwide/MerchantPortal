<?php
include("../base.php");

if(isset($REQ["action"])) {
    var_dump($ipp->TransactionsAction($REQ["action"],$REQ["id"],$REQ["action_id"],$REQ["amount"] ?? 0));
    die();
}
$merchant_data = $ipp->MerchantData();
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
                    <div class="col themed-grid-col"><button data-type="Capture"'; if(isset($merchant_data->limitations->CAPTURE->active) && $merchant_data->limitations->CAPTURE->active == '1'){ echo 'disabled'; };  echo ' class="btnCapture btnAction btn btn-success">'.$lang["COMPANY"]["PAYMENT"]["CAPTURE"].'</button> '; if(isset($merchant_data->limitations->CAPTURE->active) && $merchant_data->limitations->CAPTURE->active == '1'){ echo '<br><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                      </svg> This function have been disabled. Please contact support'; };  echo '</div>
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
                    <div class="col themed-grid-col"><button data-type="Refund"'; if(isset($merchant_data->limitations->REFUND->active) && $merchant_data->limitations->REFUND->active == '1'){ echo 'disabled'; };  echo ' class="btnRefund btnAction btn btn-warning">'.$lang["COMPANY"]["PAYMENT"]["REFUND"].'</button> '; if(isset($merchant_data->limitations->REFUND->active) && $merchant_data->limitations->REFUND->active == '1'){ echo '<br><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                      </svg> This function have been disabled. Please contact support'; };  echo '</div>
                </div>        
            ';
            }
        }
        if(!isset($IPP_CONFIG["PORTAL_LOCAL_DEACTIVATE_VOID"]) || ($IPP_CONFIG["PORTAL_LOCAL_DEACTIVATE_VOID"] !== "1")) {
            echo '<div class="row row-cols-md-2 mb-2">
            <div class="col themed-grid-col"><button data-type="Void"'; if(isset($merchant_data->limitations->VOID->active) && $merchant_data->limitations->VOID->active == '1'){ echo 'disabled'; };  echo ' class="btnAction btnVoid btn btn-warning '; if(isset($merchant_data->limitations->VOID->active) && $merchant_data->limitations->VOID->active == '1'){ echo 'disable'; };  echo '">'.$lang["COMPANY"]["PAYMENT"]["VOID"].'</button> '; if(isset($merchant_data->limitations->VOID->active) && $merchant_data->limitations->VOID->active == '1'){ echo '<br><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
              </svg> This function have been disabled. Please contact support'; };  echo '</div>
        </div>';
        }
        echo '
    </div>
    <div class="col-6 related_payments">
        <h2>'.$lang["COMPANY"]["PAYMENT"]["RELATED_PAYMENTS"].'</h2>
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
        <h2>'.$lang["COMPANY"]["PAYMENT"]["ACQUIRER_RESPONSE"].'</h2>
        <textarea class="form-control" rows="3">'; echo  $transaction_data->acquirer_data->response; echo '</textarea>
    </div>
</div>        
';
$inline_script[] = "var payment_id='".$transaction_data->transaction_id."';";
$inline_script[] = "var action_id='".$REQ["id"]."';";
echo foot();
