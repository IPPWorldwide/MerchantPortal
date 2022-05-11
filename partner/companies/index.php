<?php
include("../b.php");

if(isset($REQ["userid"])) {
    $partner->ResetMerchantPassword($REQ["company_id"],$REQ["userid"],$REQ["password"]);
    die();
}
if(isset($REQ["close"])) {
    $partner->CloseMerchant($REQ["company_id"]);
    header("Location: /partner/companies");
    die();
}

$companies = $partner->ListCompany();
echo head();
?>
    <div class="row">
        <div class="col-6">
            <h2>Companies</h2>
        </div>
        <div class="col-6 text-end">
            <a class="btn btn-success" href="add.php">Add new Company</a>
        </div>
    </div>
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
              $user = reset($value->users);
              echo "
              <td>".$value->id."</td>
              <td>".$value->security->key2."</td>
              <td>".$value->name."</td>
              <td>";
              foreach($value->domains as $subvalue) {
                  echo $subvalue->url . "<br />";
              }
              echo "</td>
              <td>".$user->username."</td>
              <td>".$value->invoices->overdue."</td>
              <td>".$value->invoices->total."</td>
              <td><a href='/partner/companies/details.php?id=".$value->id."' class='btn btn-dark'>Info</a></td>
              <td>
              <button type='button' class='btn btn-info ResetPasswordModal' data-companyname='".$value->name."' data-company='".$value->id."' data-id='".$user->id."' data-username='".$user->username."'>Reset Password</button></td>
              <td><a href='/partner/companies/?close=1&company_id=".$value->id."' class='btn btn-warning'>Close Account</a></td>
            </tr>";
          }
          ?>
          </tbody>
        </table>
      </div>

    <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" name="company_id" id="company-id" readonly>
                        <input type="hidden" name="user_id" id="user-id" readonly>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Username:</label>
                            <input type="text" class="form-control"  name="username" id="username" readonly>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Password:</label>
                            <input type="password" class="form-control checkPasswordUser" name="password" id="password">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Repeat Password:</label>
                            <input type="password" class="form-control checkPasswordUser" id="repeat-password">
                            <small id="PasswordRequirements">Password did not contain at least one lowercase letter, one uppercase letter, one numeric digit, and one special character</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeModal">Close</button>
                    <button type="button" class="btn btn-primary confirm" disabled>Change password</button>
                </div>
            </div>
        </div>
    </div>


<?php

echo foot();