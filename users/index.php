<?php
include("../base.php");

if(isset($REQ["password"])) {
    $ipp->ResetUserPassword($REQ["userid"],$REQ["password"]);
    die();
}
if(isset($REQ["access_right"])) {
    $ipp->ChangeUserAccessRight($REQ["userid"],$REQ["access_right"]);
    die();
}
if(isset($REQ["close"])) {
    $close = $ipp->CloseUser($REQ["user_id"]);
    header("Location: /users");
    die();
}
$access_rights = $ipp->GetAllAccessRights();

$companies = $ipp->ListUsers();
echo head();
echo '
    <div class="row">
        <div class="col-6">
            <h2>'.$lang["COMPANY"]["USERS"]["HEADER"].'</h2>
        </div>
        <div class="col-6 text-end">
            <a class="btn btn-success" href="add.php">'.$lang["COMPANY"]["USERS"]["ADD_NEW"].'</a>
        </div>
    </div>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
                <th scope="col">'.$lang["COMPANY"]["USERS"]["ID"].'</th>
                <th scope="col">'.$lang["COMPANY"]["USERS"]["USER_NAME"].'</th>
                <th scope="col">'.$lang["COMPANY"]["USERS"]["ADMINISTRATOR"].'</th>
                <th scope="col">'.$lang["COMPANY"]["USERS"]["FUNCTION"].'</th>
            </tr>
          </thead>
          <tbody>
          ';
          foreach($companies as $value) {
            echo "
            <tr>
                <td>".$value->id."</td>
                <td>".$value->email."</td>
                <td>".$value->admin."</td>
                <td>
                    <button type='button' class='btn btn-light ChangeAccessModal' data-username='".$value->email."' id='user_access_".$value->id."' data-id='".$value->id."' data-rights='".$value->access->id."'>".$lang["COMPANY"]["USERS"]["CHANGE_ACCESS"]."</button>
                    <button type='button' class='btn btn-info ResetPasswordModal' data-username='".$value->email."' data-id='".$value->id."'>".$lang["COMPANY"]["USERS"]["RESET_PASSWORD"]."</button>
                    <a href='/users/?close=1&user_id=".$value->id."' class='btn btn-warning'>".$lang["COMPANY"]["USERS"]["CLOSE_ACCOUNT"]."</a>
                </td>
            </tr>
            ";
          }
          echo '
          </tbody>
        </table>
      </div>

    <div class="modal fade" id="accessModal" tabindex="-1" role="dialog" aria-labelledby="accessModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="accessModalTitle"></h5>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" name="user_id" id="user-id" readonly>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">'.$lang["COMPANY"]["USERS"]["NEW_ACCESS_RIGHTS"].'</label>
                            <select class="form-control" name="access_right" id="access_right">
                            ';
                            foreach($access_rights->content->company_rules as $idx=>$rule){
                                echo '<option value="'.$idx.'">'.$rule->name.'</option>';
                            }
                            echo '
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeModal">'.$lang["COMPANY"]["USERS"]["CLOSE"].'</button>
                    <button type="button" class="btn btn-primary confirm">'.$lang["COMPANY"]["USERS"]["SET_ACCESS_RIGHTS"].'</button>
                </div>
            </div>
        </div>
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
                            <label for="recipient-name" class="col-form-label">'.$lang["COMPANY"]["USERS"]["USER_NAME"].'</label>
                            <input type="text" class="form-control"  name="username" id="username" readonly>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">'.$lang["COMPANY"]["USERS"]["PASSWORD"].'</label>
                            <input type="password" class="form-control checkPasswordUser" name="password" id="password">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">'.$lang["COMPANY"]["USERS"]["REPEAT_PASSWORD"].'</label>
                            <input type="password" class="form-control checkPasswordUser" id="repeat-password">
                            <small id="PasswordRequirements">'.$lang["COMPANY"]["USERS"]["PASSWORD_REQUIREMENTS"].'</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeModal">'.$lang["COMPANY"]["USERS"]["CLOSE"].'</button>
                    <button type="button" class="btn btn-primary confirm" disabled>'.$lang["COMPANY"]["USERS"]["CHANGE_PASSWORD"].'</button>
                </div>
            </div>
        </div>
    </div>
';
echo foot();