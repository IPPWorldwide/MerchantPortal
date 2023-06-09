<?php
$website_domain_name = $onboarding_data->{"website-domain-name"} ?? "";
$company_country = $onboarding_data->{"company-country"} ?? "";
$company_vat = $onboarding_data->{"company-vat"} ?? "";

$company_name = $onboarding_data->{"company-name"} ?? "";
$company_address = $onboarding_data->{"company-address"} ?? "";
$company_zip = $onboarding_data->{"company-zip"} ?? "";
$company_city = $onboarding_data->{"company-city"} ?? "";

$inline_script[] = "val_company_country = '$company_country';";
$countries = [
    ["dnk","92051_denmark_denmark","Denmark"],
    ["swe","92367_sweden_sweden","Sweden"],
    ["fin","92085_finland_finland","Finland"],
];
foreach($onboarding_data->key_personnel as $key=>$value) {
    $inline_script[] = "$( document ).ready(function() {
        var md5 = '".md5($value->name)."';
        if($('#person_' + md5).length === 0 && $.inArray(md5, md5people ) < 0) {
            $.post( '', { person: 1, id: '".$value->id."', name: '".$value->name."', email: '".$value->email."', address: '".$value->address."', postal: '".$value->postal."', city: '".$value->city."', country: '".$value->country."', files_passport: '".$value->files->passport."', files_driving_license_front: '".$value->files->driving_license_front."', files_driving_license_back: '".$value->files->driving_license_back."', files_address: '".$value->files->address."' })
            .done(function( person ) {
                $('#allUbos').append(person);
            });
            md5people.push(md5);
            ubos_found = true;
        }
    });";
}
if($company_country !== "" && strlen($company_vat) > 3) {
    $inline_script[] = "$( document ).ready(function() {
    setTimeout(function () {
        FindCompanyDetails();
    }, 2000);
    ";
}
?>
<div id='company'>
    <div class="step1 row url">
        <h2>URL</h2>
        <div class="col-9">
            <input class="form-control input-lg col-9" type="text" placeholder="The URL of your website" id="company-url" value="<?php echo $website_domain_name; ?>">
        </div>
        <div class="col-3">
            <button class="form-control btn btn-success col-3" data-href="country" data-validation="access_url">Confirm</button>
        </div>
    </div>
    <div class="step2 row country">
        <h2>Country of company registration</h2>
    <div class="flags">
            <?php
            foreach($countries as $value) {
                echo '<img src="icons/'.$value[1].'.png" data-country="'.$value[2].'" data-href="company_data" data-validation="company_country"';
                if($company_country !== "" && strtolower($company_country) !== strtolower($value[2]))
                    echo "style='opacity: 0.5;'";
                if($company_country !== "" && strtolower($company_country) === strtolower($value[2]))
                    echo "style='opacity: 1.0;'";
                echo '>';
            }
            ?>
        </div>
    </div>
    <div class="step3 row company_data">
        <h2>VAT code</h2>
        <div class="col-9">
            <input class="form-control input-lg col-9" id="company_vat" type="text" placeholder="Enter your local VAT number" value="<?php echo $company_vat; ?>">
        </div>
        <div class="col-3">
            <button class="form-control btn btn-success col-3" onclick="FindCompanyDetails();">Confirm</button>
        </div>
        <div class="identified_company_details">
            <div class="CompanyLoading"><img src="<?php echo $IPP_CONFIG["PORTAL_URL"]."assets/img/loading.gif"; ?>"></div>
            <div class="mb-12 row">
                    <label for="staticEmail" class="col-sm-4 col-form-label">Company Name</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control input" id="company-name" value="<?php echo $company_name; ?>">
                </div>
            </div>
            <div class="mb-12 row">
                <label for="staticEmail" class="col-sm-4 col-form-label">Address</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control input" id="company-address" value="<?php echo $company_address; ?>">
                </div>
            </div>
            <div class="mb-12 row">
                <label for="staticEmail" class="col-sm-4 col-form-label">ZIP Code</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control input" id="company-zip" value="<?php echo $company_zip; ?>">
                </div>
            </div>
            <div class="mb-12 row">
                <label for="staticEmail" class="col-sm-4 col-form-label">City</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control input" id="company-city" value="<?php echo $company_city; ?>">
                </div>
            </div>
            <div class="col-3 row">
                <button class="form-control btn btn-success col-3" data-href="ubo" data-validation="validate_for_ubo">Confirm</button>
            </div>
        </div>
    </div>
    <div class="step3 row ubo">
        <h2>Attach documents for the following UBOs:</h2>
        <div id="allUbos">
        </div>
        <div class="col-3">
            <button class="form-control btn btn-success col-3" data-group="financial" data-href="settlement">Confirm</button>
        </div>
    </div>
</div>

