$( document ).ready(function() {
    $(document).on("change", "#mcc", function () {
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
    });
    $(document).on("change", "#basket_limit", function () {
        $.ajax({
            url: GLOBAL_BASE_URL + "company/data/onboarding/update/",
            method: "POST",
            data: {
                company_id: company.id,
                api_key: company.api_key,
                field: 'basket_limit',
                value: $("#basket_limit").val()
            }
        });
    });
    $(document).on("focusout", "#delivery_timeframe", function () {
        $.ajax({
            url: GLOBAL_BASE_URL + "company/data/onboarding/update/",
            method: "POST",
            data: {
                company_id: company.id,
                api_key: company.api_key,
                field: 'delivery_timeframe',
                value: $("#delivery_timeframe").val()
            }
        });
    });
});
