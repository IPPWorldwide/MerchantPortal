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
            var_dump($value);
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
$companies = $partner->ListCompany();
$invoices = $partner->Listinvoices();
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
    </div>';
}
echo foot();