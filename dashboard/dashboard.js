(function () {
  'use strict'
})();



jQuery( document ).ready(function() {
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

});
