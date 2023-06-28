<?php
include_once "../base.php";
if(isset($REQ["id"])) {
    $invoice = $ipp->InvoiceData($REQ["id"]);

    $gateway    = new IPPGateway($_ENV["PARTNER_COMPANY_ID"],$_ENV["PARTNER_COMPANY_KEY2"]);
    $data   = [];
    $data["currency"] = $invoice->currency;
    $data["amount"] = $invoice->amount;
    $data["order_id"] = $REQ["id"];
    $data["transaction_type"] = "ECOM";
    $data["ipn"] = "https://api.ippeurope.com/company/invoice/paid/";
    $data = $gateway->checkout_id($data);
    $data_url = $data->checkout_id;
    $cryptogram = $data->cryptogram;
    $action = "?";
    echo json_encode(array("checkout_id" => $data_url,"cryptogram" => $cryptogram));
    die();
}
$invoices = $ipp->Listinvoices();
echo head();
$actions->get_action("invoices");
$actions->get_action("theme_replacement");

echo '
    <div class="row">
        <div class="col-6">
            <h2>'.$lang["COMPANY"]["INVOICES"]["HEADER"].'</h2>
        </div>
    </div>
    <div class="table-responsive">
    ';
    if(isset($REQ["transaction_id"])) {
        echo '            <div class="col md-12 alert-success rounded text-muted alert">
                <div>'.$lang["COMPANY"]["INVOICES"]["RECEIVED_PAYMENT"].'</div>
            </div>';
    }
    echo '
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
                if($value->cancelled !== "0")
                    echo $lang["COMPANY"]["INVOICES"]["CANCELLED"];
                elseif($value->paid !== "0")
                    echo $lang["COMPANY"]["INVOICES"]["PAID_TEXT"];
                else
                    echo $lang["COMPANY"]["INVOICES"]["UNPAID"];
              echo "</td>
              <td>
                    <a class=\"btn btn-info\" href=\"show.php?id=".$value->id."\">".$lang["COMPANY"]["INVOICES"]["SHOW"]."</a>
                    ";
              if($value->cancelled !== "1" && $value->paid !== "1" && isset($IPP_CONFIG["PARTNER_COMPANY_ID"]) && strlen($IPP_CONFIG["PARTNER_COMPANY_ID"]) == 14)
                  echo "
                    <button class='btn btn-success btnPayInvoice' data-id='".$value->id."'>".$lang["COMPANY"]["INVOICES"]["PAY_INVOICE"]."</button>
                    ";
              echo "
              </td>
            </tr>";
            }
            echo '
            </tbody>
        </table>
    </div>
';
echo '
    <div class="modal fade" id="settingsPaymentModal" tabindex="-1" role="dialog" aria-labelledby="settingsPaymentModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="PaymentLongTitle"></h5>
                </div>
                <div class="modal-body">
                    <form action="index.php" class="search-form paymentWidgets" data-brands="VISA MASTER" data-theme="divs"><center><img src="'.THEME.'/assets/img/loading.gif"></center></form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeModal">'.$lang["COMPANY"]["DATA"]["CLOSE"].'</button>
                </div>
            </div>
        </div>
    </div>';
echo foot();
