<?php
include("../b.php");

$companies = $partner->ListCompany();
echo head();
?>
      <h2>Companies</h2>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th scope="col">Company ID</th>
              <th scope="col">Secret Key 2</th>
              <th scope="col">Company Name</th>
              <th scope="col">Domains</th>
              <th scope="col">Login</th>
              <th scope="col">Unpaid invoices</th>
              <th scope="col">Total invoices</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <tbody>
          <?php
          echo "<tr>";
          foreach($companies as $value) {
              echo "
              <td>".$value->id."</td>
              <td>".$value->security->key2."</td>
              <td>".$value->name."</td>
              <td>";
              foreach($value->domains as $subvalue) {
                  echo $subvalue->url . "<br />";
              }
              echo "</td>
              <td>".reset($value->users)->username."</td>
              <td>".$value->invoices->overdue."</td>
              <td>".$value->invoices->total."</td>
              <td><a href='/partner/companies/details.php?id=".$value->id."' class='btn btn-dark'>Info</a></td>
            </tr>";
          }
          ?>
          </tbody>
        </table>
      </div>
<?php

echo foot();