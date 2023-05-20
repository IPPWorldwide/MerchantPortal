var ubos_found = false;

function access_url() {
    if(/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test($("#company-url").val())){
        return true;
    } else {
        $("#company-url").css("border","1px red solid");
        return false;
    }
}
function company_country() {
    return true;
}
function FindCompanyDetails() {
    $("#onboarding_form .company_data .identified_company_details").css("display","inline");

    var request = $.ajax({
        url: "",
        method: "POST",
        data: { country: val_company_country, vat : $("#company_vat").val() },
        dataType: "html"
    });

    request.done(function( msg ) {
        $( "#log" ).html( msg );
    });

    request.fail(function( jqXHR, textStatus ) {
        alert( "Request failed: " + textStatus );
    });
}
function validate_for_ubo() {
    if(ubos_found)
        return true;
    else {
        window.alert("Could not identify any UBO. Application stopped");
        return false;
    }
}
