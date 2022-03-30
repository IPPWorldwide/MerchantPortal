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
            console.log(data);
            console.log(data.meta_data.company.vat);
            $("#company_id").val(data.id);
            $("#companyname").val(data.meta_data.company.name);
            $("#companyvat").val(data.meta_data.company.vat);
            $("#addressaddress").val(data.meta_data.address.address);
            $("#addresspostal").val(data.meta_data.address.postal);
            $("#addresscity").val(data.meta_data.address.city);
            $("#addresscountry").val(data.meta_data.address.country);
            $("#addressaddress").val(data.meta_data.address.address);
            $("#addressaddress").val(data.meta_data.address.address);

        }
    });
});
