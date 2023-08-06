(function () {
    'use strict'
})();
$('.ResetPasswordModal').on('click', function () {
    var button = $(this);
    $('#passwordModal').modal('show');
    $('#passwordModal .modal-title').text('New password for ' + button.data('username'));
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
            userid: $('#passwordModal #user-id').val(),
            password: $( "#password" ).val()
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

$('.ChangeAccessModal').on('click', function () {
    var button = $(this);
    $('#accessModal').modal('show');
    $('#accessModal .modal-title').text('New access rights for ' + button.data('username'));
    $('#accessModal #user-id').val(button.data('id'));
    $('#accessModal #access_right').val(button.attr('data-rights'));
});

$("#accessModal .closeModal").on('click', function() {
    $('#passwordModal').modal('hide');
});

$("#accessModal .confirm").on("click", function() {
    $.ajax({
        method: "POST",
        url: "?",
        data: {
            userid: $('#accessModal #user-id').val(),
            access_right: $( "#access_right" ).val()
        }
    }).done(function () {
        $("#user_access_" + $('#accessModal #user-id').val()).attr("data-rights", $( "#access_right" ).val());
        $('#accessModal').modal('hide');
    });
});
