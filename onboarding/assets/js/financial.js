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
});
