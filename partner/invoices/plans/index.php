<?php
include("../../b.php");

$subscription_plans = $partner->ListSubscriptionPlans();
echo head();
?>
    <h2>Invoice Plans</h2>
    <a class="btn btn-success" href="add.php">Add new Scheme</a>
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Currency</th>
                <th scope="col">Monthly Fee</th>
                <th scope="col">Tnx Fee</th>
                <th scope="col">Status</th>
            </tr>
            </thead>
            <tbody>
            <?php
            echo "<tr>";
            foreach($subscription_plans as $value) {
                echo "
              <td>".$value->id."</td>
              <td>".$value->name."</td>
              <td>".$value->currency_txt."</td>
              <td>".$value->amount_readable."</td>
              <td>".$value->amount_tnx_readable."</td>
              <td><a href='/partner/invoices/plans/details.php?id=".$value->id."' class='btn btn-dark'>Info</a></td>
            </tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
<?php

echo foot();