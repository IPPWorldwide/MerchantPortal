function UploadFinanceData() {

    var formData = new FormData();
    formData.append('field', 'processing-file');
// Attach file
    formData.append('file', $('#processing-history')[0].files[0]);
    formData.append('user_id', user.id);
    formData.append('session_id', user.session_id);

    $.ajax({
        url: 'http://localhost:8091/company/data/onboarding/update/',
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
