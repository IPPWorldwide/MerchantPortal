var ubos_found = false;
var md5people = [];

function access_url() {
    if(/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test($("#company-url").val())){
        $(".website_check_url").html($("#company-url").val());
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
    $(".flags img").css("opacity","0.5");
    $this.css("opacity","1.0");
    val_company_country = $this.attr("data-country");
    return true;
}
function FindCompanyDetails() {
    $("#onboarding_form .company_data .identified_company_details").css("display","inline");
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
                $.post( GLOBAL_BASE_URL + "company/data/onboarding/personnel/add/", {
                    company_id: company.id,
                    api_key: company.api_key,
                    politically_exposed: 0, agreement_signature: 0,
                    full_name: value.name, email: "", date_of_birth: "", key_personnel_address: value.address, postal: value.postal, city: value.city, country: value.country
                })
                    .done(function( data ) {
                        console.log(data);
                        $.post( "", { person: 1, id: data.content.owner_id, name: value.name, address: value.address, postal: value.postal, city: value.city, country: value.country })
                            .done(function( person ) {
                                $("#allUbos").append(person);
                            });
                    });

                md5people.push(md5);
                ubos_found = true;
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
                ubos_found = true;
            }
        });
        $("#company-name").val(msg.company.name);
        $("#company-address").val(msg.company.address);
        $("#company-zip").val(msg.company.postal);
        $("#company-city").val(msg.company.city);
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
$( document ).ready(function() {
    $(document).on("click",".delete_person", function() {
        var div = $(this).parent().parent().attr("id");
        var id = $(this).parent().parent().attr("data-id");
        var md5 = $(this).parent().parent().attr("data-md5");
        $("#" + div).remove();
        $.ajax({
            url: GLOBAL_BASE_URL + "company/data/onboarding/personnel/remove/",
            method: "POST",
            data: {
                owner_id: id,
                company_id: company.id,
                api_key: company.api_key
            }
        });
        md5people = jQuery.grep(md5people, function(value) {
            return value != md5;
        });
    });
    $(document).on("change", ".passport", function() {
        var id = $(this).parent().parent().parent().attr("data-id");
        var div = $(this).parent().parent().parent().attr("id");
        var formData = new FormData();
        formData.append('owner_id', id);
        formData.append('company_id', company.id);
        formData.append('api_key', company.api_key);
        formData.append('file-passport', $(this)[0].files[0]);
        $.ajax({
            url: GLOBAL_BASE_URL + "company/data/onboarding/personnel/file/",
            method: "POST",
            data: formData,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
        });
    });
    $(document).on("change", ".address", function() {
        var id = $(this).parent().parent().parent().attr("data-id");
        var div = $(this).parent().parent().parent().attr("id");
        var formData = new FormData();
        formData.append('owner_id', id);
        formData.append('company_id', company.id);
        formData.append('api_key', company.api_key);
        formData.append('file-address-documentation', $(this)[0].files[0]);
        $.ajax({
            url: GLOBAL_BASE_URL + "company/data/onboarding/personnel/file/",
            method: "POST",
            data: formData,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
        });
    });
    $(document).on("change", ".email", function() {
        var id = $(this).parent().parent().parent().attr("data-id");
        var div = $(this).parent().parent().parent().attr("id");
        $.ajax({
            url: GLOBAL_BASE_URL + "company/data/onboarding/personnel/update/",
            method: "POST",
            data: {
                owner_id: id,
                company_id: company.id,
                api_key: company.api_key,
                field: 'email',
                value: $(this).val()
            },
        });
    });
});
