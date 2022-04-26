<?php
include("../b.php");

if(isset($REQ["userid"])) {
    var_dump($partner->ResetUserPassword($REQ["userid"],$REQ["password"]));
    die();
}
if(isset($REQ["close"])) {
    $close = $partner->CloseUser($REQ["user_id"]);
    var_dump($close);
    die();
    header("Location: /partner/users");
    die();
}

$companies = $partner->ListUsers();
echo head();
?>
    <div class="row">
        <div class="col-6">
            <h2>Users</h2>
        </div>
        <div class="col-6 text-end">
            <a class="btn btn-success" href="add.php">Add new User</a>
        </div>
    </div>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">User name</th>
                <th scope="col">Administrator</th>
                <th scope="col">#</th>
            </tr>
          </thead>
          <tbody>
          <?php
          foreach($companies as $value) {
            echo "
            <tr>
                <td>".$value->id."</td>
                <td>".$value->email."</td>
                <td>".$value->admin."</td>
                <td>
                    <button type='button' class='btn btn-info ResetPasswordModal' data-username='".$value->email."' data-id='".$value->id."'>Reset Password</button>
                    <a href='/partner/users/?close=1&user_id=".$value->id."' class='btn btn-warning'>Close Account</a>
                </td>
            </tr>
            ";
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