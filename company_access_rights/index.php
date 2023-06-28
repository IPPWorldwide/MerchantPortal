<?php
include_once "../base.php";
echo head();
$actions->get_action("access_rights");
$actions->get_action("theme_replacement");

$access_rights = $ipp->GetAllAccessRights();
echo '
    <div class="row">
        <div class="col-6">
            <h2>Access Rights</h2>
        </div>
        <div class="col-6 text-end">
            <a class="btn btn-success" href="add.php">'.$lang["COMPANY"]["ACCESS_RIGHTS"]["ADD_NEW"].'</a>
        </div>
    </div>

<div class="row">
    ';
foreach($access_rights->content->company_rules as $idx=>$rule){ ?>
        <div class="col-xxl-4">
            <div class="card mb-4">
                <div class="card-header"><h3><?= $rule->name ?></h3></div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php
                            $rights = (array)$rule->rights;
                            foreach($rights as $idx=>$access_right){
                                if(!isset($access_rights->content->all_rules->{$access_right})){
                                    continue;
                                }
                        ?>
                            <li class="list-group-item">
                                <h4><?= $access_rights->content->all_rules->{$access_right}->name ?></h4>
                                <p class="m-0 p-0 text-muted"><?= $access_rights->content->all_rules->{$access_right}->description ?></p>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<?php echo foot(); ?>
