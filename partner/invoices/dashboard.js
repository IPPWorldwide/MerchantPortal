(function () {
    'use strict'
})();
$('.SelectCustomer').on('change', function () {
    $.ajax({
        url: '?',
        data: {
            action: "search",
            company_id: $(this).val(),
        },
        dataType: "json",
        cache: false,
        success: function (data) {
            $("#company_id").val(data.id);
            if(typeof data.meta_data.company !== "undefined") {
                $("#companyname").val(data.meta_data.company.name);
                $("#companyvat").val(data.meta_data.company.vat);
            }
            if(typeof data.meta_data.address !== "undefined") {
                $("#addressaddress").val(data.meta_data.address.address);
                $("#addresspostal").val(data.meta_data.address.postal);
                $("#addresscity").val(data.meta_data.address.city);
                $("#addresscountry").val(data.meta_data.address.country);
                $("#addressaddress").val(data.meta_data.address.address);
            }
        }
    });
});
$(".btnExternalInvoice").on("click", function() {
    console.log("Solved");
    $("#importModal").modal("show");
    $("#importModal #invoiceId").val($(this).data("id"));
})
$(".connectInvoice").on("click", function() {
    $(".connectInvoice").html("Wait ...").attr("disabled","disabled");
    $.ajax({
        url: '?',
        data: {
            provider: $(this).attr("data-provider"),
            id: $("#invoiceId").val(),
            customer: $("#customerId").val(),
            add_invoice: 1
        },
        dataType: "json",
        cache: false,
        success: function (data) {
            window.location.reload();
        }
    });
});

$(".btnSettings").on("click",function() {
    $("#invoiceSettingsModal").modal("show");
});

$("#invoiceSettingsModal .closeModal").on("click",function() {
    $("#invoiceSettingsModal").modal("hide");
});
$("#invoiceSettingsModal .confirm").on("click",function() {
    $.ajax({
        url: '?',
        data: $("#invoiceSettingsModal form").serialize(),
        dataType: "json",
        cache: false,
        success: function (data) {
            $("#invoiceSettingsModal").modal("hide");
        }
    });
});

$(".productdata").on("keyup", function() {
    var AllProductData = true;
    $(".productdata").each(function() {
        if(AllProductData && $(this).val() === "")
            AllProductData = false;
    });
    if(AllProductData)
        $("#add_invoice").removeAttr("disabled");
    else
        $("#add_invoice").attr("disabled","disabled");
    console.log(AllProductData);
});
