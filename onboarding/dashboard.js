(function () {
  'use strict'

  $("#onboarding_form button").click(function() {
    var $this = $(this);
    var group = $this.data("group");
    var href = $this.data("href");
    if(typeof group === "undefined")
      group = $("#onboarding_form #onboarding_menu li[active='1']").data("group");
    if(typeof $this.data("validation") === "string") {
        var f = window[$this.data("validation")]();
        if(!f)
          return;
    }
    $("#onboarding_form #onboarding_menu ul li ol").removeAttr("active").css("display","none").css("font-weight","normal");
    $("." + group + " li").css("font-weight","normal");
    $("." + group + "").attr("active","1");
    $("." + group + " span").css("font-weight","bold");
    $("." + group + " li[data-href='" + href + "']").css("font-weight","bold");
    $("." + group + " ol").css("display","inline");
    $("#onboarding_form .content div").css("display","none");
    $("#onboarding_form .content #"+ group).css("display","inline");
    $("#onboarding_form .content #"+ group +" ." + href + "").css("display","flex");
    $("#onboarding_form .content #"+ group +" ." + href + " div").css("display","flex");
  });

})()
function access_url() {
  if(/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test($("#company-url").val())){
    return true;
  } else {
    $("#company-url").css("border","1px red solid");
    return false;
  }
}
