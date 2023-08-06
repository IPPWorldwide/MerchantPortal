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
    $(".confirmOnboardingAllDataSeen").on("click", function(e) {
        HandleApplication(e, "ApproveCompany",true)
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
    $("#OnboardingCompanyFinancials").css("display","none");
    var data = $("#OnboardingForm").serialize() + '&ApproveApplication=' + state;

    if(overwrite)
        data += "&continue_with_errors=true"

    if(state==="ApproveCompany") {
        data += "&confirm_application=1";
    }

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
            }
            else if(data.content.company_data) {
                $("#OnboardingCompanyFinancials .warnings").css("display","none");
                $("#OnboardingCompanyFinancials .Charts").css("display","none");
                $("#OnboardingCompanyFinancials .ProfitData").css("display","none");
                $("#OnboardingCompanyFinancials").css("display","");
                if(data.content.invalid_companydata === true) {
                    $("#OnboardingCompanyFinancials .warnings").css("display","");
                    $("#onboardingApplicationModal .modal-footer.withErrors").css("display","");
                    $("#OnboardingCompanyFinancials .warning").html(data.content.reason);
                } else {
                    $("#OnboardingCompanyFinancials .Charts").css("display","");
                    $("#OnboardingCompanyFinancials .ProfitData").css("display","");
                    $("#onboardingApplicationModal .modal-footer.modal-footer-all-good").css("display","");
                    var TotalLiquidity = 100;
                    TotalLiquidity = TotalLiquidity - data.content.key.liquidity;
                    if(TotalLiquidity<0)
                        TotalLiquidity = 0;
                    var ctx = document.getElementById("liquidity");
                    var myChart = new Chart(ctx, {
                        type: "doughnut",
                        data: {
                            labels: ["Liquidity %"],
                            datasets: [
                                {
                                    label: ["Liquidity %"],
                                    data: [data.content.key.liquidity, TotalLiquidity],
                                    backgroundColor: ["rgba(255, 99, 132, 0.2)"]
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            circumference: Math.PI,
                            rotation: -Math.PI,
                        }
                    });
                    var TotalReturnAssets = 100;
                    TotalReturnAssets = TotalReturnAssets - data.content.key.returnOnAssets;
                    if(TotalReturnAssets<0)
                        TotalReturnAssets = 0;
                    var ctxa = document.getElementById("returnAssets");
                    var myCharta = new Chart(ctxa, {
                        type: "doughnut",
                        data: {
                            labels: ["Return On Assets %"],
                            datasets: [
                                {
                                    label: ["return On Assets %"],
                                    data: [data.content.key.returnOnAssets, TotalReturnAssets],
                                    backgroundColor: ["rgba(255, 99, 132, 0.2)"]
                                }
                            ]
                        },
                        options: {
                            circumference: Math.PI,
                            rotation: -Math.PI,
                            cutoutPercentage: 64
                        }
                    });

                    var TotalReturnEquity = 100;
                    TotalReturnEquity = TotalReturnEquity - data.content.key.returnOnEquity;
                    if(TotalReturnEquity<0)
                        TotalReturnEquity = 0;
                    var ctxa = document.getElementById("returnEquity");
                    var myCharta = new Chart(ctxa, {
                        type: "doughnut",
                        data: {
                            labels: ["Return On Equity %"],
                            datasets: [
                                {
                                    label: ["Return On Equity %"],
                                    data: [data.content.key.returnOnEquity, TotalReturnEquity],
                                    backgroundColor: ["rgba(255, 99, 132, 0.2)"]
                                }
                            ]
                        },
                        options: {
                            circumference: Math.PI,
                            rotation: -Math.PI,
                            cutoutPercentage: 64
                        }
                    });
                    var TotalSolidity = 100;
                    TotalSolidity = TotalSolidity - data.content.key.solidity;
                    if(TotalSolidity<0)
                        TotalSolidity = 0;
                    var ctxa = document.getElementById("solidity");
                    var myCharta = new Chart(ctxa, {
                        type: "doughnut",
                        data: {
                            labels: ["Solidity %"],
                            datasets: [
                                {
                                    label: ["Solidity %"],
                                    data: [data.content.key.solidity, TotalSolidity],
                                    backgroundColor: ["rgba(255, 99, 132, 0.2)"]
                                }
                            ]
                        },
                        options: {
                            circumference: Math.PI,
                            rotation: -Math.PI,
                            cutoutPercentage: 64
                        }
                    });
                    $("#OnboardingCompanyFinancials .GrossVolume").html(data.content.raw.gross_volume);
                    $("#OnboardingCompanyFinancials .ProfitAfterTax").html(data.content.raw.profitLoss);
                    $("#OnboardingCompanyFinancials .FreeCashAfterDebt").html(data.content.raw.free_liquidity);
                }
            } else {
                $("#onboardingApplicationModal .NoIssuesFound").css("display","");
            }
            console.log(data);
        }
    });
    e.preventDefault();
    return false;
}
