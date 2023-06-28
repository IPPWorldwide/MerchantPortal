<?php
include_once "../base.php";
$result         = $REQ["result"] ?? "ALL";
echo head();
$actions->get_action("cardholder_subscriptions");
$actions->get_action("theme_replacement");
echo '
<h2>'.$lang["COMPANY"]["CARDHOLDER_SUBSCRIPTIONS"]["HEADER"].'</h2>

<form action="/subscriptions" method="GET">
    <div class="form-group">
        <label for="result">'.$lang["COMPANY"]["CARDHOLDER_SUBSCRIPTIONS"]["RESULT"].'</label>
        <select id="result" name="result">
        <option>'.$lang["COMPANY"]["CARDHOLDER_SUBSCRIPTIONS"]["ALL"].'</option>
        <option>'.$lang["COMPANY"]["CARDHOLDER_SUBSCRIPTIONS"]["ACK"].'</option>
        <option>'.$lang["COMPANY"]["CARDHOLDER_SUBSCRIPTIONS"]["NOK"].'</option>
    </select>
    </div>
    <input type="submit" value="Change view" class="btn btn-primary">
</form>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th scope="col">'.$lang["COMPANY"]["CARDHOLDER_SUBSCRIPTIONS"]["TRANSACTION_ID"].'</th>
                <th scope="col">'.$lang["COMPANY"]["CARDHOLDER_SUBSCRIPTIONS"]["CARDHOLDER"].'</th>
                <th scope="col">'.$lang["COMPANY"]["CARDHOLDER_SUBSCRIPTIONS"]["CARD_HASHED"].'</th>
                <th scope="col">'.$lang["COMPANY"]["CARDHOLDER_SUBSCRIPTIONS"]["EXPIRY"].'</th>
                <th scope="col">'.$lang["COMPANY"]["CARDHOLDER_SUBSCRIPTIONS"]["STATUS"].'</th>
            </tr>
        </thead>
        <tbody>
        ';
        foreach($ipp->SubscriptionsList($result) as $value) {
            echo "<tr>
                <td>".$value->transaction_id."</td>
                <td>".$value->card->holder."</td>
                <td>".$value->card->hashed."</td>
                <td>".$value->card->expmonth." ".$value->card->expyear."</td>
                <td>".$value->result."</td>
            </tr>";
        }
        echo '
        </tbody>
    </table>
</div>
';
echo foot();
