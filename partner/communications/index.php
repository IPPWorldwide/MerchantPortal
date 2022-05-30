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

<?php

echo foot();