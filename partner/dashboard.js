/* globals Chart:false, feather:false */

(function () {
  'use strict'
  feather.replace({ 'aria-hidden': 'true' })
})();

let graphs = document.querySelectorAll('.col');
let renders = [];
graphs.forEach(ele => {
  renders[ele.dataset.sequence] = (sequence) => {
    var dashboardElement = $("body").find('[data-sequence='+sequence+']');
    ReloadLive(sequence,dashboardElement.data("data"), dashboardElement.data("type"), dashboardElement.data("source"));
  }
});
renders.forEach((e, key) => {
  e(key);
});
function ReloadLive(sequence, data, type, source) {
     $.get( "graphs.php", { graph: sequence, data: data, type: type, source: source })
     .done(function( getData ) {
       let json = JSON.parse(getData);
       var ctxe = document.getElementById(`chart${sequence}`);
       var ctx = ctxe.getContext('2d');
         if(type==="GraphBar") {
             var myChart = new Chart(ctx, {
                 type: json.type,
                 data: json.data,
                 options: json.options
             });
         }
       if(type==="GraphLine") {
         var myChart = new Chart(ctx, {
           type: json.type,
           data: json.data,
           options: json.options
         });
       }
       else if(type==="Number") {
         ctx.fillStyle = "blue";
         ctx.font = "22px Arial";
         ctx.textAlign = 'center';
         ctx.textBaseline = 'middle';
         ctx.fillText(json.data.number, (ctxe.width / 2), (ctxe.height / 2));
       }
       setTimeout(function(){
         ReloadLive(sequence, data, type);
       }, 60000);
     });
 }
 $(".DashboardRemoveElement").on("click", function() {
     var $this = $(this).parent().parent();
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
   } else {
     $(".AddNewElementToPage").css("display","none");
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
$(".btnAddElement").on("click", function() {
});
$(function() {
  $('.selectpicker').selectpicker();
});
