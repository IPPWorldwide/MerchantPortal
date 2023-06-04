function sendContracts() {
    $.ajax({
        url: GLOBAL_BASE_URL + "company/data/onboarding/update/",
        method: "POST",
        data: {
            company_id: company.id,
            api_key: company.api_key,
            field: 'commercial-mcc',
            value: $("#mcc").val()
        }
    });
    $.ajax({
        type: 'POST',
        url: GLOBAL_BASE_URL + '/company/data/onboarding/confirm/',
        dataType: "json",
        data: { api_key: api_key, company_id: company_id },
        success: function (response) {
            AddShadowOnComplete();
            $.each( onboarding_extensions, function( key, value ) {
                $.ajax({
                    type: 'POST',
                    url: value,
                    dataType: "json",
                    data: {
                        company_id: company.id,
                        api_key: company.api_key
                    },
                });
            });
        }
    });
    return true;
}
