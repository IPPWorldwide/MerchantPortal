(function () {
  'use strict'
  $("#onboarding_form [data-href]").click(function() {
    console.log("Clicked");
    var $this = $(this);
    var group = $this.data("group");
    var href = $this.data("href");
    ChangePage($this, group,href);
  });
})()
function ChangePage($this, group,href) {
  if(typeof group === "undefined")
    group = $("#onboarding_form #onboarding_menu li[active='1']").attr("data-group");
  if(typeof $this.data("validation") === "string") {
    var f = window[$this.data("validation")]();
    if(!f)
      return;
  }
  $("#onboarding_form #onboarding_menu ul li").removeAttr("active");
  $("#onboarding_form #onboarding_menu ul li span").css("font-weight","unset");
  $("#onboarding_form #onboarding_menu ul li ol").removeAttr("active").css("display","none").css("font-weight","normal");
  $("." + group + " li").css("font-weight","normal");
  $("." + group + "").attr("active","1");
  $("." + group + " span").css("font-weight","bold");
  $("." + group + " li[data-href='" + href + "']").css("font-weight","bold");
  $("." + group + " ol").css("display","inline");
  $("#onboarding_form .content #welcome").css("display","none");
  $("#onboarding_form .content div[active='true']").css("display","none");
  $("#onboarding_form .content #"+ group).css("display","inline");
  $("#onboarding_form .content #"+ group +" ." + href + "").attr("active",true).css("display","flex");
}
