function UploadFinanceData() {

    var formData = new FormData();
    formData.append('field', 'processing-file');
    formData.append('file', $('#processing-history')[0].files[0]);
    formData.append('user_id', user.id);
    formData.append('session_id', user.session_id);

    $.ajax({
        url: GLOBAL_BASE_URL + "company/data/onboarding/update/",
        type: 'POST',
        processData: false, // important
        contentType: false, // important
        dataType : 'json',
        data: formData,
        success: function(data){
            console.log(data);
        }
    });
    return true;
}
$( document ).ready(function() {
    $( "#iban" )
        .on( "focusout", function() {
            $.ajax({
                url: GLOBAL_BASE_URL + "company/data/onboarding/update/",
                method: "POST",
                data: {
                    company_id: company.id,
                    api_key: company.api_key,
                    field: 'bank-iban',
                    value: $("#iban").val()
                }
            });
        } );
    $( "#swift" )
        .on( "focusout", function() {
            $.ajax({
                url: GLOBAL_BASE_URL + "company/data/onboarding/update/",
                method: "POST",
                data: {
                    company_id: company.id,
                    api_key: company.api_key,
                    field: 'bank-bic',
                    value: $("#swift").val()
                }
            });
        } );
    $( "#settlementFrequency" )
        .on( "focusout", function() {
            $.ajax({
                url: GLOBAL_BASE_URL + "company/data/onboarding/update/",
                method: "POST",
                data: {
                    company_id: company.id,
                    api_key: company.api_key,
                    field: 'bank-frequency',
                    value: $("#settlementFrequency").val()
                }
            });
        } );
    $( "#settlementFrequency" )
        .on( "focusout", function() {
            $.ajax({
                url: GLOBAL_BASE_URL + "company/data/onboarding/update/",
                method: "POST",
                data: {
                    company_id: company.id,
                    api_key: company.api_key,
                    field: 'bank-processing-history',
                    value: $("#earlierProcessing").val()
                }
            });
        } );
    $( "#earlierProcessing" )
        .on( "focusout", function() {
            $.ajax({
                url: GLOBAL_BASE_URL + "company/data/onboarding/update/",
                method: "POST",
                data: {
                    company_id: company.id,
                    api_key: company.api_key,
                    field: 'earlierProcessing',
                    value: $("#earlierProcessing").val()
                }
            });
        } );
    $(document).on("change", "#bank-screenshot", function() {
        var id = $(this).parent().parent().parent().attr("data-id");
        var div = $(this).parent().parent().parent().attr("id");
        var formData = new FormData();
        formData.append('company_id', company.id);
        formData.append('api_key', company.api_key);
        formData.append('field', "bank-documentation");
        formData.append('file', $("#bank-screenshot")[0].files[0]);
        $.ajax({
            url: GLOBAL_BASE_URL + "company/data/onboarding/update/",
            method: "POST",
            data: formData,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
        });
    });
    $(document).on("change", "#processing-history", function() {
        var id = $(this).parent().parent().parent().attr("data-id");
        var div = $(this).parent().parent().parent().attr("id");
        var formData = new FormData();
        formData.append('company_id', company.id);
        formData.append('api_key', company.api_key);
        formData.append('field', "processing-history");
        formData.append('file', $("#processing-history")[0].files[0]);
        $.ajax({
            url: GLOBAL_BASE_URL + "company/data/onboarding/update/",
            method: "POST",
            data: formData,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
        });
    });
});
