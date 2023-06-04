function sendContracts() {
    $.ajax({
        type: 'POST',
        url: GLOBAL_BASE_URL + '/company/data/onboarding/confirm/',
        dataType: "json",
        data: { api_key: company.api_key, company_id: company.id },
        success: function (response) {
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
