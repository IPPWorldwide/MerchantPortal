(function () {
    'use strict'
    feather.replace({ 'aria-hidden': 'true' })
})();
$('.ResetPasswordModal').on('click', function () {
    var button = $(this);
    $('#passwordModal').modal('show');
    $('#passwordModal .modal-title').text('New password for ' + button.data('companyname'));
    $('#passwordModal #username').val(button.data('username'));
    $('#passwordModal #company-id').val(button.data('company'));
    $('#passwordModal #user-id').val(button.data('id'));
});

$("#passwordModal .closeModal").on('click', function() {
    $('#passwordModal').modal('hide');
});

$( ".checkPasswordUser" ).keyup(function() {
    if($( "#password" ).val() == $( "#repeat-password" ).val()) {
        CheckPassword($( "#password" ).val());
    } else {
        $("#passwordModal .modal-footer .confirm").attr("disabled", true);
        $("#passwordModal #PasswordRequirements").css("display","inline");
    }
});

$("#passwordModal .confirm").on("click", function() {
    $.ajax({
        method: "POST",
        url: "?",
        data: {
            company_id: $('#passwordModal #company-id').val(),
            userid: $('#passwordModal #user-id').val(),
            email: $('#passwordModal #username').val(),
            password: $("#passwordModal #password").val()
        }
    }).done(function () {
        $('#passwordModal').modal('hide');
    });
});

function CheckPassword(inputtxt)
{
    var paswd = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{12,99}$/;
    if(inputtxt.match(paswd))
    {
        $("#passwordModal .modal-footer .confirm").removeAttr("disabled");
        $("#passwordModal #PasswordRequirements").css("display","none");
    }
    else
    {
        $("#passwordModal .modal-footer .confirm").attr("disabled", true);
        $("#passwordModal #PasswordRequirements").css("display","inline");
    }
}

$(".AccessCompanyAccount").on("click", function() {
    $.ajax({
        method: "POST",
        url: "?",
        dataType: "json",
        data: {
            company_id: $(this).attr("data-company"),
            access_company: 1
        }
    }).done(function (data) {
        console.log(data["content"].user_id);
        window.location.href = PORTAL_URL + "partner/companies/?redirect=1&user_id=" + data["content"].user_id + "&session_id=" + data["content"].session_id;
    });
});
