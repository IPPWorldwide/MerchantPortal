(function () {
  'use strict'
})();
$("#checkbox_right_ALL").on("change", function() {
  console.log($(this).is(":checked"));
    if($(this).is(":checked") === true) {
      $(".access_rights").prop('checked', true);
    } else {
      $(".access_rights").prop('checked', false);
    }
});