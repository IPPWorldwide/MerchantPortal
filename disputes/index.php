<?php
include("../base.php");
$state   = $REQ["dispute_state"] ?? "ALL";
$status  = $REQ["dispute_status"] ?? "ALL";

echo head();
echo '
    <h2>'.$lang["COMPANY"]["DISPUTES"]["HEADER"].'</h2>
    <form action="/disputes" method="GET">
        <div class="form-group">
            <label for="payment_type">'.$lang["COMPANY"]["DISPUTES"]["TYPE"].'</label>
            <select id="dispute_state" name="dispute_state">
                <option>'.$lang["COMPANY"]["DISPUTES"]["OPEN"].'</option>
                <option>'.$lang["COMPANY"]["DISPUTES"]["CLOSED"].'</option>
            </select>
        </div>
        <div class="form-group">
            <label for="payment_result">'.$lang["COMPANY"]["DISPUTES"]["STATUS"].'</label>
            <select id="dispute_status" name="dispute_status">
                <option>'.$lang["COMPANY"]["DISPUTES"]["RECEIVED"].'</option>
                <option>'.$lang["COMPANY"]["DISPUTES"]["REPRESENTED"].'</option>
                <option>'.$lang["COMPANY"]["DISPUTES"]["FIRST"].'</option>
                <option>'.$lang["COMPANY"]["DISPUTES"]["SECOND"].'</option>
                <option>'.$lang["COMPANY"]["DISPUTES"]["LOST"].'</option>
            </select>
        </div>
        <input type="submit" value="'.$lang["COMPANY"]["DISPUTES"]["CHANGE_VIEW"].'" class="btn btn-primary">
    </form>

    <table class="table table-striped table-sm">
        <thead>
        <tr>
            <th scope="col">'.$lang["COMPANY"]["DISPUTES"]["ID"].'</th>
            <th scope="col">'.$lang["COMPANY"]["DISPUTES"]["RECEIVED_DATE"].'</th>
            <th scope="col">'.$lang["COMPANY"]["DISPUTES"]["RESPOND_DAYS_LEFT"].'</th>
            <th scope="col">'.$lang["COMPANY"]["DISPUTES"]["TRANSACTION_ID"].'</th>
            <th scope="col">'.$lang["COMPANY"]["DISPUTES"]["DISPUTED_AMOUNT"].'</th>
            <th scope="col">'.$lang["COMPANY"]["DISPUTES"]["ORDER_AMOUNT"].'</th>
            <th scope="col">'.$lang["COMPANY"]["DISPUTES"]["CURRENCY"].'</th>
            <th scope="col">'.$lang["COMPANY"]["DISPUTES"]["STATUS"].'</th>
        </tr>
        </thead>
        <tbody>';
        foreach($ipp->ListDisputes($state, $status) as $value) {
            var_dump($value);

            echo "<tr class='align-middle'>";
            echo "<td><a href='/disputes/data.php?id=".$value->id."' class='btn btn-dark'>".$lang["COMPANY"]["DISPUTES"]["INFO"]."</a></td>";
            echo "<td>".$value->timestamp->received_readable."</td>";
            echo "<td>".$value->timestamp->next_update_days." ".$lang["COMPANY"]["DISPUTES"]["DAYS"]."</td>";
            echo "<td>".$value->transaction->id."</td>";
            echo "<td>".$value->amount_readable."</td>";
            echo "<td>".$value->transaction->amount_readable."</td>";
            echo "<td>".$value->transaction->currency->id."</td>";
            echo "<td><img class='small-icon' src='";
            if($value->status == "lost") {
                echo "/theme/".$_ENV["THEME"]."/assets/img/chargeback_lost.png";
            }
            elseif($value->status == "won") {
                echo "/theme/".$_ENV["THEME"]."/assets/img/chargeback_won.png";
            }
            else {
                echo "/theme/".$_ENV["THEME"]."/assets/img/chargeback_info.png";
            }
            echo "'></td>";
            echo "</tr>";
        }
        echo '
        </tbody>
    </table>
    </div>
';
echo foot();