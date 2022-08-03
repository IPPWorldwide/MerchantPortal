<?php
include("../b.php");
if(isset($REQ["add_invoice"])) {

    $invoice = $plugins->ListSpecificInvoice($REQ["provider"],$REQ["id"]);
    $data = $partner->AddInvoiceProvider($REQ["customer"],
        [
            "name"  => $invoice["company"]["name"],
            "vat"   => $invoice["company"]["vat"]
        ],
        [
        "date" => $invoice["date"]["invoiced"],
        "period_start" => $invoice["date"]["due"],
        "period_end" => $invoice["date"]["due"]
    ],
    [
        [
            "name" => $invoice["data"]["description"],
            "qty"  => 1,
            "price" => $invoice["amount"]["readable"]
        ]
    ],
    [
        "address"   => $invoice["company"]["address"],
        "postal"    => $invoice["company"]["postal"],
        "city"      => $invoice["company"]["city"],
        "country"   => $invoice["company"]["country"],
    ],$invoice["currency"]["code"],$REQ["provider"],$invoice["guid"]);
    if(count($invoice["related"]) > 0) {
        foreach($invoice["related"] as $value) {
            $partner->AddInvoiceProvider($REQ["customer"],
                [
                    "name"  => $value["company"]["name"],
                    "vat"   => $value["company"]["vat"]
                ],
                [
                    "date" => $value["date"]["invoiced"],
                    "period_start" => $value["date"]["due"],
                    "period_end" => $value["date"]["due"]
                ],
                [
                    [
                        "name" => $value["data"]["description"],
                        "qty"  => 1,
                        "price" => $value["amount"]["readable"]
                    ]
                ],
                [
                    "address"   => $value["company"]["address"],
                    "postal"    => $value["company"]["postal"],
                    "city"      => $value["company"]["city"],
                    "country"   => $value["company"]["country"],
                ],$value["currency"]["code"],$REQ["provider"],$value["guid"]);
        }
    }
    echo json_encode($invoice);
    die();
}
if(isset($REQ["partner_merchant_id"])) {
    $partner_data = $partner->PartnerData();
    $partner->UpdateData($REQ,$partner_data->name);


    include(BASEDIR . "controller/IPPConfig.php");
    $config = new IPPConfig();
    $config->UpdateConfig("partner_company_id",$REQ["partner_merchant_id"]);
    $config->UpdateConfig("partner_company_key2", $REQ["partner_merchant_key2"]);
    $config = $config->WriteConfig();
    unset($REQ["IPPCONFIG"]);

    echo "{}";
    die();
}
$partner_data = $partner->PartnerData();
$companies = $partner->ListCompany();
$invoices = $partner->ListInvoices();
$bookeeping = $plugins->ListInvoices();
$bookeeping_providers = [];
$known_invoices = [];

echo head();
echo '
    <div class="row">
        <div class="col-6">
            <h2>'.$lang["PARTNER"]["INVOICES"]["HEADER"].'</h2>
        </div>
        <div class="col-6 text-end">
            <a class="btn btn-success" href="add.php">'.$lang["PARTNER"]["INVOICES"]["ADD_NEW"].'</a>
            <button class="btn btn-success btnSettings">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                    <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                    <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
                </svg>
            </button>
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
              if(!in_array($value->provider->name.$value->provider->guid,$known_invoices)) {
                  $known_invoices[] = $value->provider->name.$value->provider->guid;
              }
            }
            echo '
            </tbody>
        </table>
    </div>
';
if(count((array)$bookeeping)>0) {
    echo '
    <div class="row">
        <div class="col-6">
            <h2>'.$lang["PARTNER"]["INVOICES"]["BOOKING_SYSTEM_HEADER"].'</h2>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th scope="col">'.$lang["PARTNER"]["INVOICES"]["ID"].'</th>
                <th scope="col">'.$lang["PARTNER"]["INVOICES"]["COMPANY"].'</th>
                <th scope="col">'.$lang["PARTNER"]["INVOICES"]["AMOUNT"].'</th>
                <th scope="col">'.$lang["PARTNER"]["INVOICES"]["PAID"].'</th>
                <th scope="col">'.$lang["PARTNER"]["INVOICES"]["FUNCTIONS"].'</th>
            </tr>
            </thead>
            <tbody>
            <tr>';
    foreach($bookeeping as $value) {
        if(in_array($value->provider->slug.$value->guid,$known_invoices)) {
            continue;
        }
        if(!in_array($value->provider->name, $bookeeping_providers))
            $bookeeping_providers[$value->provider->slug] = $value->provider->name;

        echo "
          <td>" . $value->id . "</td>
          <td>" . $value->contact->name . "</td>
          <td>" . $value->amount->readable. " " . $value->currency->txt . "</td>
          <td>" . date("Y-m-d", $value->date->due) . "</td>
          <td>";
            echo $value->paid->is_paid;
        echo "</td>
          <td><button class=\"btn btn-info btnExternalInvoice\" data-provider='".$value->provider->slug."' data-id='".$value->guid."'>" . $lang["PARTNER"]["INVOICES"]["IMPORT"] . "</button></td>
        </tr>";
    }
    echo '
            </tbody>
        </table>
    </div>
';
    echo '
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">'.$lang["PARTNER"]["INVOICES"]["IMPORT_HEADER"].'</h5>
                </div>
                <div class="modal-body">
                    <input type="HIDDEN" id="invoiceId" value="">
                    <select class="form-select" name="customerId" id="customerId" class="col themed-grid-col">
                    ';
                    foreach($companies as $key=>$value)
                        echo '<option value="'.$value->id.'">'.$value->name.'</option>';
                    echo '
                    </select><br />
                    ';
                    foreach($bookeeping_providers as $key=>$value) {
                        echo '<button class="btn btn-primary mb-3 connectInvoice" data-provider="'.$key.'">'.$lang["PARTNER"]["INVOICES"]["CONNECT_WITH"].' '.$value.'</button>';
                    }
                    echo '
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeModal" data-bs-dismiss="modal">'.$lang["PARTNER"]["PLUGINS"]["CLOSE"].'</button>
                </div>
            </div>
        </div>
    </div>
';
}
echo '<div class="modal fade" id="invoiceSettingsModal" tabindex="-1" role="dialog" aria-labelledby="invoiceSettingsModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceSettingsModalLongTitle">'.$lang["PARTNER"]["INVOICES"]["PARTNER_INVOICES"].'</h5>
            </div>
            <div class="modal-body">
                <form>
                <input name="field" type="hidden" class="form-control" value="meta">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">'.$lang["PARTNER"]["INVOICES"]["PAYMENT_SLIP"].'</label>
                        <textarea name="meta[invoicetext]" class="form-control">'; echo $partner_data->meta_data->meta->invoicetext ?? ""; echo '</textarea>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">'.$lang["PARTNER"]["INVOICES"]["MERCHANT_ID"].'</label>
                        <input name="partner_merchant_id" class="form-control" value="'; echo $partner_data->merchant_id ?? ""; echo '">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">'.$lang["PARTNER"]["INVOICES"]["MERCHANT_KEY2"].'</label>
                        <input name="partner_merchant_key2" class="form-control" value="'; echo $partner_data->merchant_key ?? ""; echo '">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closeModal">'.$lang["PARTNER"]["INVOICES"]["MODAL_CLOSE"].'</button>
                <button type="button" class="btn btn-primary confirm">'.$lang["PARTNER"]["INVOICES"]["MODAL_SUBMIT_BTN"].'</button>
            </div>
        </div>
    </div>
</div>
    ';
echo foot();