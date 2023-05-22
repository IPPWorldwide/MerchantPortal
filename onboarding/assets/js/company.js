var ubos_found = false;

function access_url() {
    if(/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test($("#company-url").val())){
        $.ajax({
            url: GLOBAL_BASE_URL + "company/data/onboarding/update/",
            method: "POST",
            data: {
                company_id: company.id,
                api_key: company.api_key,
                field: 'website-domain-name',
                value: $("#company-url").val()
            }
        });
        return true;
    } else {
        $("#company-url").css("border","1px red solid");
        return false;
    }
}
function company_country($this) {
    $.ajax({
        url: GLOBAL_BASE_URL + "company/data/onboarding/update/",
        method: "POST",
        data: {
            company_id: company.id,
            api_key: company.api_key,
            field: 'company-country',
            value: $this.attr("data-country")
        }
    });
    return true;
}
var md5people = [];
function FindCompanyDetails() {
    $("#onboarding_form .company_data .identified_company_details .CompanyLoading").css("display","inline");
    $("#onboarding_form .company_data .identified_company_details .row").css("display","none");
    var request = $.ajax({
        url: "",
        method: "POST",
        data: { country: val_company_country, vat : $("#company_vat").val() },
        dataType: "json"
    });
    $.ajax({
        url: GLOBAL_BASE_URL + "company/data/onboarding/update/",
        method: "POST",
        data: {
            company_id: company.id,
            api_key: company.api_key,
            field: 'company-vat',
            value: $("#company_vat").val()
        }
    });
    request.done(function( msg ) {
        $("#onboarding_form .company_data .identified_company_details .CompanyLoading").css("display","none");
        $("#onboarding_form .company_data .identified_company_details .row").removeAttr("style");
        $("#onboarding_form [data-href='ubo']").removeAttr("disabled");
        $.each(msg.kyc.directors, function( index, value ) {
            var md5 = $.md5(value.name);
            if($("#person_" + md5).length === 0 && $.inArray(md5, md5people ) < 0) {
                $.post( "", { person: 1, id: 1, name: value.name, address: value.address, postal: value.postal, city: value.city, country: value.country })
                .done(function( person ) {
                    $("#allUbos").append(person);
                });
                md5people.push(md5);
            }
        });
        $.each(msg.kyc.realOwners, function( index, value ) {
            var md5 = $.md5(value.name);
            if($("#person_" + md5).length === 0 && $.inArray(md5, md5people ) < 0) {
                $.post( "", { person: 1, id: 1, name: value.name, address: value.address, postal: value.postal, city: value.city, country: value.country })
                    .done(function( person ) {
                        $("#allUbos").append(person);
                    });
                md5people.push(md5);
            }
        });
    });

    request.fail(function( jqXHR, textStatus ) {
        alert( "Request failed: " + textStatus );
    });
}
function validate_for_ubo() {
    if(ubos_found) {
        $.ajax({
            url: GLOBAL_BASE_URL + "company/data/onboarding/update/",
            method: "POST",
            data: {
                company_id: company.id,
                api_key: company.api_key,
                field: 'company-name',
                value: $("#company-name").val()
            }
        });
        $.ajax({
            url: GLOBAL_BASE_URL + "company/data/onboarding/update/",
            method: "POST",
            data: {
                company_id: company.id,
                api_key: company.api_key,
                field: 'company-address',
                value: $("#company-address").val()
            }
        });
        $.ajax({
            url: GLOBAL_BASE_URL + "company/data/onboarding/update/",
            method: "POST",
            data: {
                company_id: company.id,
                api_key: company.api_key,
                field: 'company-zip',
                value: $("#company-zip").val()
            }
        });
        $.ajax({
            url: GLOBAL_BASE_URL + "company/data/onboarding/update/",
            method: "POST",
            data: {
                company_id: company.id,
                api_key: company.api_key,
                field: 'company-city',
                value: $("#company-city").val()
            }
        });
        return true;
    }
    else {
        window.alert("Could not identify any UBO. Application stopped");
        return false;
    }
}
