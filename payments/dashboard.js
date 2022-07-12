(function () {
  'use strict'
  feather.replace({ 'aria-hidden': 'true' });
})();

jQuery( document ).ready(function() {
  $(".btnAction").on("click", function () {
    console.log($(this).data("type"));
    $.ajax({
      method: "POST",
      url: "?id="+action_id,
      beforeSend: function() {
        $('.loader').removeClass('d-none');
        $('.related-payment-table').addClass('d-none');
      },
      data: {
        action: $(this).data("type").toLowerCase(),
        id: payment_id,
        action_id: action_id,
        amount: $('#amount' + $(this).data("type")).val(),
      }
    }).done(function (html) {
      $('.loader').addClass('d-none');
      $('.related-payment-table').removeClass('d-none');
      $('#related_payments').html(html);
      $('#passwordModal').modal('hide');
    });
  });
});

