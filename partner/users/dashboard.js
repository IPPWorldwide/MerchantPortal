(function () {
    'use strict'
    feather.replace({ 'aria-hidden': 'true' })
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

$('.AccessRights').on('click', function () {
    var button = $(this);
    $('#accessModal').modal('show');
    $('#accessModal .modal-title').text('New access rights for ' + button.data('username'));
    $('#accessModal #user-id').val(button.data('id'));
    $('#passwordModal #compliance_admin').val(button.data('compliance'));
    if(button.attr('data-compliance') === "1") {
        $('#compliance_admin').attr("checked","checked")
        console.log(button.attr('data-compliance'));
    } else {
        $('#compliance_admin').removeAttr("checked");
    }
});

$("#accessModal .closeModal").on('click', function() {
    $('#accessModal').modal('hide');
});


$("#accessModal .confirm").on("click", function() {
    $.ajax({
        method: "POST",
        url: "?",
        data: {
            userid: $('#accessModal #user-id').val(),
            compliance: $( "#compliance_admin" ).is(":checked")
        }
    }).done(function () {
        $('#accessModal').modal('hide');
        if($( "#compliance_admin" ).is(":checked"))
            $('body').find("[data-id=\"" + $('#accessModal #user-id').val() + "\"]").attr("data-compliance","1");
        else
            $('body').find("[data-id=\"" + $('#accessModal #user-id').val() + "\"]").attr("data-compliance","0");
    });
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

