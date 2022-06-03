
$('.forgotPassword').on('click', function () {
    var button = $(this);
    $('#passwordModal').modal('show');
    $('#passwordModal .modal-title').text('Forgot password');
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
    $('#passwordModal .ResetEmailSent').css('display','inline');
    $('#passwordModal .form-group').css('display','none');
    $.ajax({
        method: "POST",
        url: "?",
        data: {
            reset_email: $('#passwordModal #username').val()
        }
    }).done(function () {
        setTimeout(function () {
            if (newState == -1) {
                $('#passwordModal').modal('hide');
                $('#passwordModal .ResetEmailSent').css('display','none');
                $('#passwordModal .form-group').css('display','inline');
                $('#passwordModal').modal('hide');
            }
        }, 10000);

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