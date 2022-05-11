<?php
include("../b.php");

if(isset($REQ["close"])) {
    $partner->CloseCommuncationTemplate($REQ["template_id"]);
    header("Location: /partner/communications");
    die();
}

$templates = $partner->ListTemplates();

echo head();
?>
    <div class="row">
        <div class="col-6">
            <h2>Communication Templates</h2>
        </div>
        <div class="col-6 text-end">
            <a class="btn btn-success" href="data.php">Add new Template</a>
        </div>
    </div>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th scope="col">Hook</th>
              <th scope="col">Communication Type</th>
              <th scope="col">Title</th>
              <th scope="col">Active</th>
              <th scope="col">#</th>
            </tr>
          </thead>
          <tbody>
          <?php
          echo "<tr>";
          foreach($templates as $value) {
              echo "
              <td>".$value->hook."</td>
              <td>".$value->type."</td>
              <td>".$value->title."</td>
              <td>".$value->active."</td>
              <td>
                <a href='data.php?template_id=".$value->id."' class='btn btn-info'>Edit</a>
                <a href='?close=1&template_id=".$value->id."' class='btn btn-warning'>Close Account</a>
              </td>
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