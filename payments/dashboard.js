(function () {
  'use strict'
  feather.replace({ 'aria-hidden': 'true' });
})();

jQuery( document ).ready(function() {
  $(".btnAction").on("click", function () {
    console.log($(this).data("type"));
    $.ajax({
      method: "POST",
      url: "?",
      data: {
        action: $(this).data("type").toLowerCase(),
        id: payment_id,
        action_id: action_id,
        amount: $('#amount' + $(this).data("type")).val(),
      }
    }).done(function () {
      $('#passwordModal').modal('hide');
    });
  });
});
