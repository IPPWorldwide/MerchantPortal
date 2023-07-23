/* globals Chart:false, feather:false */

(function () {
  'use strict'
  feather.replace({ 'aria-hidden': 'true' })
})();
$(".DashboardRemoveElement").on("click", function() {
     var $this = $(this).parent();
     var sequence = $this.attr("data-sequence");
     $.post( "?", { action: "removeElement", sequence: sequence });
     $("div").filter(function() {
         return parseInt($(this).attr("data-sequence")) > sequence;
     }).each(function(){
         newId = parseInt($(this).attr("data-sequence")) - 1;
         $(this).attr("data-sequence", newId);
     })
         ;

     $this.remove();
 });
 $(".btnChangeDashboard").on("click", function() {
   $(".settings").toggle();
   if($(".AddNewElementToPage").css('display') == 'none') {
     $(".AddNewElementToPage").css("display","contents");
     $(".DashboardRemoveElement").css("display","block");
   } else {
     $(".AddNewElementToPage").css("display","none");
     $(".DashboardRemoveElement").css("display","none");
   }
 });
 $(".btnAddElement").on("click", function() {
   AddDashboardElement();
});
 function AddDashboardElement() {
   $.post( "?", { action: "addElement", data: $(".selectpicker").val(), source: $('.ElementContent').attr('data-source'), type: $(".ElementType").val(), total: $(".row .chartscol.dashboard").length })
     .done(function( data ) {
       $(".DashboardElements").append(data);
     });
 }
 function AddBtnAvailable() {
   if($(".ElementType").val() !== "0" && $(".ElementContent").val() !== "0") {
     $(".btnAddElement").removeAttr("disabled");
   } else {
     $(".btnAddElement").attr("disabled", "disabled");
   }
 }
$(".ElementType").on("change", function() {
  AddBtnAvailable();
});
$(".ElementContent").on("change", function() {
  AddBtnAvailable();
    let slctor = 'select[name="ElementContent"]'

    $(slctor).on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
        let data = $(`${slctor} option`).eq(clickedIndex).attr("data-source");
        $(".ElementContent").attr("data-source", data);
    })
});
