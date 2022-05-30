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
echo '
    <div class="row">
        <div class="col-6">
            <h2>'.$lang["PARTNER"]["COMPANIES"]["HEADER"].'</h2>
        </div>
        <div class="col-6 text-end">
            <a class="btn btn-success" href="add.php">'.$lang["PARTNER"]["COMPANIES"]["ADD_NEW"].'</a>
        </div>
    </div>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th scope="col">'.$lang["PARTNER"]["COMPANIES"]["COMPANY_ID"].'</th>
              <th scope="col">'.$lang["PARTNER"]["COMPANIES"]["SECRET_KEY"].'</th>
              <th scope="col">'.$lang["PARTNER"]["COMPANIES"]["COMPANY_NAME"].'</th>
              <th scope="col">'.$lang["PARTNER"]["COMPANIES"]["DOMAINS"].'</th>
              <th scope="col">'.$lang["PARTNER"]["COMPANIES"]["LOGIN"].'</th>
              <th scope="col">'.$lang["PARTNER"]["COMPANIES"]["UNPAID_INVOICES"].'</th>
              <th scope="col">'.$lang["PARTNER"]["COMPANIES"]["TOTAL_INVOICES"].'</th>
              <th scope="col">'.$lang["PARTNER"]["COMPANIES"]["STATUS"].'</th>
            </tr>
          </thead>
          <tbody>
          <tr>';
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
              <td><a href='/partner/companies/details.php?id=".$value->id."' class='btn btn-dark'>".$lang["PARTNER"]["COMPANIES"]["INFO"]."</a></td>
              <td>
              <button type='button' class='btn btn-info ResetPasswordModal' data-companyname='".$value->name."' data-company='".$value->id."' data-id='".$user->id."' data-username='".$user->username."'>".$lang["PARTNER"]["COMPANIES"]["RESET_PASSWORD"]."</button></td>
              <td><a href='/partner/companies/?close=1&company_id=".$value->id."' class='btn btn-warning'>".$lang["PARTNER"]["COMPANIES"]["CLOSE_ACCOUNT"]."</a></td>
            </tr>";
          }
          ?>
          </tbody>
        </table>
      </div>
<?php
echo '
    <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" name="company_id" id="company-id" readonly>
                        <input type="hidden" name="user_id" id="user-id" readonly>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">'.$lang["PARTNER"]["COMPANIES"]["MODAL_USERNAME"].'</label>
                            <input type="text" class="form-control"  name="username" id="username" readonly>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">'.$lang["PARTNER"]["COMPANIES"]["MODAL_PASSWORD"].'</label>
                            <input type="password" class="form-control checkPasswordUser" name="password" id="password">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">'.$lang["PARTNER"]["COMPANIES"]["MODAL_REPEAT_PASSWORD"].'</label>
                            <input type="password" class="form-control checkPasswordUser" id="repeat-password">
                            <small id="PasswordRequirements">'.$lang["PARTNER"]["COMPANIES"]["MODAL_PASSWORD_DESCRIPTION"].'</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeModal">'.$lang["PARTNER"]["COMPANIES"]["MODAL_CLOSE"].'</button>
                    <button type="button" class="btn btn-primary confirm" disabled>'.$lang["PARTNER"]["COMPANIES"]["MODAL_SUBMIT_BTN"].'</button>
                </div>
            </div>
        </div>
    </div>
';
echo foot();