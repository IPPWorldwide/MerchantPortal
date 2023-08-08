<?php

?>
<div id='contract'>
    <div class="step1 row our_contracts">
        <div class="col-12">
            <h2><?php echo $lang["COMPANY"]["ONBOARDING"]["CONTRACT_TITLE"] ?></h2>
            <?php echo $lang["COMPANY"]["ONBOARDING"]["CONTRACT_CONTAINS_EXPLAINER"] ?>
            <?php echo $lang["COMPANY"]["ONBOARDING"]["CONTRACT_CONTAINS_LIST"] ?>
        </div>
        <div class="col-12">
            <button class="form-control btn btn-success col-3 sendContract" data-href="contracts_sent" data-validation="sendContracts"><?php echo $lang["COMPANY"]["ONBOARDING"]["CONTRACT_SEND_TO_SIGN"] ?></button>
        </div>
    </div>
    <div class="step2 row contracts_sent">
        <h2><?php echo $lang["COMPANY"]["ONBOARDING"]["CONTRACT_SENT_HEADER"] ?></h2>
        <p>
            <?php echo $lang["COMPANY"]["ONBOARDING"]["CONTRACT_SENT_EXPLAINER"] ?>
        </p>
    </div>
</div>
