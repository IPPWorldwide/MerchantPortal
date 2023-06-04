var validatedWebsite = false;
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
    $(".checkWebsite").on("click", function() {
        checkWebsite();
    });
    checkWebsiteStatus()
});
function checkWebsite() {
    validatedWebsite = true;
    $(".checkWebsite").attr("disabled","disabled");
    $(".ValidatedWebsiteChecks").removeAttr("disabled");
}
function checkWebsiteStatus() {
    $.ajax({
        url: "http://localhost:8091/company/data/",
        method: "POST",
        data: {
            user_id: user.id,
            session_id: user.session_id
        }
    })
        .done(function(data) {
            var onboarding_data = data.content.onboarding_data.website;
            if(onboarding_data.pending_customer === 1) {
                if(onboarding_data.lorem_ipsum === true || onboarding_data.privacy_policy === false || onboarding_data.product === false || onboarding_data.terms === false) {
                    (".checkWebsite").removeAttr("disabled");
                }
                $(".ValidatedWebsiteChecks").removeAttr("disabled");
            }
        });
}
