(function () {
    'use strict'
    feather.replace({ 'aria-hidden': 'true' })
})();
$('.SelectCustomer').on('change', function () {
    console.log($(this));
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