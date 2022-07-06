(function () {
  'use strict'
  feather.replace({ 'aria-hidden': 'true' });
})();

$(".btnPayInvoice").on("click",function() {
  $("#settingsPaymentModal").modal("show");
  $.ajax({
    method: "POST",
    url: "?",
    dataType: 'json',
    data: {
    id: $(this).data("id")
  }
  }).done(function (data) {
    var checkout_id = data.checkout_id;
    var cryptogram = data.cryptogram;
    $.getScript("https://pay.ippeurope.com/pay.js?checkoutId=" + checkout_id + "&cryptogram=" + cryptogram, function() { });
  });
});
