<?php
include("../b.php");

$list_scheme_accounts = $partner->ListKickback();
echo head();
$actions->get_action("partner_kickback");

echo '
    <div class="row">
        <div class="col-6">
            <h2>'.$lang["PARTNER"]["KICKBACK"]["HEADER"].'</h2>
        </div>
    </div>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th scope="col">'.$lang["PARTNER"]["KICKBACK"]["TRANSACTION_ID"].'</th>
              <th scope="col">'.$lang["PARTNER"]["KICKBACK"]["ACTION_ID"].'</th>
              <th scope="col">'.$lang["PARTNER"]["KICKBACK"]["COST"].'</th>
              <th scope="col">'.$lang["PARTNER"]["KICKBACK"]["PROFIT"].'</th>
              <th scope="col">'.$lang["PARTNER"]["KICKBACK"]["SETTLED"].'</th>
            </tr>
          </thead>
          <tbody>';
            foreach($list_scheme_accounts as $value) {
                foreach($value->share->transactions as $tnx) {
                    echo '       
                    <tr>
                      <td>'.$tnx->id.'</td>
                      <td>'.$tnx->action->id.'</td>
                      <td>'.(($tnx->costs->acquirer+$tnx->costs->fixed)/100).'</td>
                      <td>'.($tnx->profit->value/100).'</td>
                      <td>'.$tnx->settled.'</td>
                    </tr>
                    ';
                }
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
                            <input type="text" class="form-control"  name="username" id="username">
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
