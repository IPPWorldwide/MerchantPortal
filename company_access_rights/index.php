<?php
include("../base.php");
echo head();
$access_rights = $ipp->GetAllAccessRights();
?>
<h2>Access Rights</h2>
<div class="row">
    <?php foreach($access_rights->content->company_rules as $idx=>$rule){ ?>
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