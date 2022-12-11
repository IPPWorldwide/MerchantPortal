<?php
include("../b.php");

if(isset($REQ["userid"]) && isset($REQ["compliance"])) {
    $partner->SetUserSettings($REQ["userid"],$REQ["compliance"]);
    die();
}
elseif(isset($REQ["userid"])) {
    $partner->ResetUserPassword($REQ["userid"],$REQ["password"]);
    die();
}
if(isset($REQ["close"])) {
    $partner->CloseUser($REQ["user_id"]);
    header("Location: /partner/users");
    die();
}

$companies = $partner->ListUsers();
echo head();
$actions->get_action("partner_users");

echo '
    <div class="row">
        <div class="col-6">
            <h2>Users</h2>
        </div>
        <div class="col-6 text-end">
            <a class="btn btn-success" href="add.php">'.$lang["PARTNER"]["USERS"]["ADD_NEW"].'</a>
        </div>
    </div>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
                <th scope="col">'.$lang["PARTNER"]["USERS"]["ID"].'</th>
                <th scope="col">'.$lang["PARTNER"]["USERS"]["USERNAME"].'</th>
                <th scope="col">'.$lang["PARTNER"]["USERS"]["FUNCTIONS"].'</th>
            </tr>
          </thead>
          <tbody>
          ';
          foreach($companies as $value) {
            echo "
            <tr>
                <td>".$value->id."</td>
                <td>".$value->email."</td>
                <td>
                ";
            if($value->active === "1") {
                echo "
                    <button type='button' class='btn btn-info ResetPasswordModal' data-username='".$value->email."' data-id='".$value->id."'>".$lang["PARTNER"]["USERS"]["RESET_PASSWORD"]."</button>
                    <button type='button' class='btn btn-warning AccessRights' data-compliance='".$value->compliance."' data-username='".$value->email."'  data-id='".$value->id."'>".$lang["PARTNER"]["USERS"]["ACCESS_RIGHTS"]."</button>
                    <a href='/partner/users/?close=1&user_id=".$value->id."' class='btn btn-danger'>".$lang["PARTNER"]["USERS"]["CLOSE_ACCOUNT"]."</a>
            ";
            } else {
                echo ICON_INFO . " This account have been disabled.";
            }
            echo "
                </td>
            </tr>
            ";
          }
          echo '
          </tbody>
        </table>
      </div>

    <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" name="user_id" id="user-id" readonly>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">'.$lang["PARTNER"]["USERS"]["MODAL_USERNAME"].'</label>
                            <input type="text" class="form-control"  name="username" id="username" readonly>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">'.$lang["PARTNER"]["USERS"]["MODAL_PASSWORD"].'</label>
                            <input type="password" class="form-control checkPasswordUser" name="password" id="password">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">'.$lang["PARTNER"]["USERS"]["MODAL_REPEAT_PASSWORD"].'</label>
                            <input type="password" class="form-control checkPasswordUser" id="repeat-password">
                            <small id="PasswordRequirements">'.$lang["PARTNER"]["USERS"]["MODAL_PASSWORD_DESCRIPTION"].'</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeModal">'.$lang["PARTNER"]["USERS"]["MODAL_CLOSE"].'</button>
                    <button type="button" class="btn btn-primary confirm" disabled>'.$lang["PARTNER"]["USERS"]["MODAL_SUBMIT_BTN"].'</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="accessModal" tabindex="-1" role="dialog" aria-labelledby="accessModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" name="user_id" id="user-id" readonly>
                        <div class="form-group form-check form-switch">
                            <input type="checkbox" class="form-check-input" name="compliance_admin" id="compliance_admin" value="1">
                            <label for="compliance_admin" class="form-check-label">'.$lang["PARTNER"]["USERS"]["MODAL_COMPLIANCE_ADMIN"].'</label><br />
                            <small>'.$lang["PARTNER"]["USERS"]["MODAL_COMPLIANCE_ADMIN_DESCRIPTION"].'</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeModal">'.$lang["PARTNER"]["USERS"]["MODAL_CLOSE"].'</button>
                    <button type="button" class="btn btn-primary confirm">'.$lang["PARTNER"]["USERS"]["MODAL_SUBMIT_BTN_SAVE"].'</button>
                </div>
            </div>
        </div>
    </div>
';
echo foot();
