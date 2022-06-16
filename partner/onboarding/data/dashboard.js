(function () {
    'use strict'
    feather.replace({ 'aria-hidden': 'true' })
})();
$(document).ready(function() {
    $(".ApproveApplication").on("click",function(e) {
        HandleApplication(e,"Approve");
    });
    $(".DeclineApplication").on("click",function(e) {
        HandleApplication(e, "Decline");
    });
    $(".closeModal").on("click", function() {
        $(".modal").modal("hide");
    });
    $(".confirmOnboarding").on("click", function(e) {
        HandleApplication(e, "Approve",true)
    });
});

function HandleApplication(e, state, overwrite=false) {
    $(".DeclinedPersons").css("display","none");
    $("#onboardingApplicationModal .NoIssuesFound").css("display","none");
    $("#onboardingApplicationModal .Declined").css("display","none");
    $("#onboardingApplicationModal .modal-footer").css("display","none");
    $("#onboardingApplicationModal .modal-body .IssueIdentified").css("display","none");
    $("#onboardingApplicationModal .dataLoading").css("display","block");
    $("#onboardingApplicationModal").modal("show");
    var data = $("#OnboardingForm").serialize() + '&ApproveApplication=' + state;

    if(overwrite)
        data += "&continue_with_errors=true"

    $.ajax({
        type: "POST",
        url: "?",
        dataType: "json",
        data: data,
        success: function(data)
        {
            $("#onboardingApplicationModal .dataLoading").css("display","none");
            if(state === "Decline") {
                $("#onboardingApplicationModal .Declined").css("display","");
            } else if(data.content.invalid_keypersonel) {
                $("#onboardingApplicationModal .modal-body .IssueIdentified").css("display","");
                $("#onboardingApplicationModal .modal-footer").css("display","");
                $(".DeclinedPersons tbody").empty();
                $.each(data.content.persons, function (key, data) {
                    $(".DeclinedPersons tbody").append("<tr><td>" + data.name + "</td><td>" + data.reason + "</td></tr>");
                });
                $(".DeclinedPersons").css("display","inline-table");
            } else {
                $("#onboardingApplicationModal .NoIssuesFound").css("display","");
            }
            console.log(data);
        }
    });
    e.preventDefault();
    return false;
}