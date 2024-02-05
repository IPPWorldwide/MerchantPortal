<?php
include_once "../base.php";

echo head();
$actions->get_action("payouts");
$actions->get_action("theme_replacement");

echo '
<div class="row row-cols-md-2 mb-2">
            <div class="col themed-grid-col">
                      <h2>'.$lang["COMPANY"]["PAYOUTS"]["HEADER"].'</h2>
            </div>
            <div class="col themed-grid-col d-flex flex-row-reverse">
                <input type="button" class="btn btn-secondary " onclick="array2excel()" value="'.$lang["COMPANY"]["EXPORT_TABLE"].'" />
            </div>
        </div>
    <table class="table table-striped table-sm" id="tnx_list">
        <thead>
        <tr>
            <th scope="col">'.$lang["COMPANY"]["PAYOUTS"]["DATE"].'</th>
            <th style="width:1px"></th>
            <th style="width:1px"></th>
            <th style="width:1px"></th>
            <th style="width:1px"></th>
';
if(!isset($IPP_CONFIG["PORTAL_LOCAL_HIDE_TOTAL_VOLUME"]) || (isset($IPP_CONFIG["PORTAL_LOCAL_HIDE_TOTAL_VOLUME"]) && $IPP_CONFIG["PORTAL_LOCAL_HIDE_TOTAL_VOLUME"] !== "1")) {
    echo '
            <th scope="col">'.$lang["COMPANY"]["PAYOUTS"]["GROSS_VOLUME"].'</th>
';
}
echo '      
            <th scope="col">'.$lang["COMPANY"]["PAYOUTS"]["CALCULATED_FEE"].'</th>
            <th scope="col">'.$lang["COMPANY"]["PAYOUTS"]["SETTLEMENT"].'</th>
            <th scope="col">'.$lang["COMPANY"]["PAYOUTS"]["SETTLED"].'</th>
        </tr>
        </thead>
        <tbody>
';
        foreach($ipp->ListPayouts() as $value) {
            echo "<tr class='align-middle' onClick=\"ShowPayouts('".trim($value->id)."')\">";
//                echo "<td><a href='/payouts/data.php?id=".$value->id."' class='btn btn-dark'>".$lang["COMPANY"]["PAYOUTS"]["INFO"]."</a></td>";
                echo "<td>".date("Y-m-d",$value->dates->release->time)."</td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
            if(!isset($IPP_CONFIG["PORTAL_LOCAL_HIDE_TOTAL_VOLUME"]) || (isset($IPP_CONFIG["PORTAL_LOCAL_HIDE_TOTAL_VOLUME"]) && $IPP_CONFIG["PORTAL_LOCAL_HIDE_TOTAL_VOLUME"] !== "1")) {
                echo "<td>".$value->amount->gross->amount_readable."</td>";
            }
                echo "<td>".$value->amount->fee->amount_readable."</td>";
                echo "<td>".$value->amount->expected_settlement->amount_readable."</td>";
                echo "<td>";
                echo "<img src='";
                if($value->settled)
                    echo "/theme/".$_ENV["THEME"]."/assets/img/yes.png";
                else
                    echo "/theme/".$_ENV["THEME"]."/assets/img/no.png";
                echo "'>";
                echo "</td>";
            echo "</tr>";
            echo "<tr id='".trim($value->id)."' class='hide'>";
                echo "<td colspan='5'>";
                    echo "<table class='table table-striped table-sm'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th></th>";
                                echo "<th>Method</th>";
                                echo "<th>Transaction ID</th>";
                                echo "<th>Order ID</th>";
                                echo "<th>Amount</th>";
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                            foreach($value->related->transactions as $related_transactions) {
                                echo "<tr>";
                                    echo "<td></td>";
                                    echo "<td>$related_transactions->method</td>";
                                    echo "<td>$related_transactions->transaction_id</td>";
                                    echo "<td>$related_transactions->order_id</td>";
                                    echo "<td>".number_format($related_transactions->amount/100,2,",","")."</td>";
                                echo "</tr>";
                            }
                        echo "</tbody>";
                    echo "</table>";
                echo "</td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
<?php
echo foot();
