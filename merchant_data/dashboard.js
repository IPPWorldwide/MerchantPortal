(function () {
    'use strict'
    feather.replace({ 'aria-hidden': 'true' })
})();
$(".btnAcquirerSettings").on("click",function(event) {
    event.preventDefault();
    $('#settingsAcquirerModal .modal-title').text($(this).data("title"));
    $("#settingsAcquirerModal #acquirer-id").val($(this).data("id"));
    $("#settingsAcquirerModal form .stdFormsSetup").remove();
    var acquirer_values;
    if(typeof $(this).attr("data-field-values") !== undefined)
        acquirer_values = JSON.parse($(this).attr("data-field-values"));
    $.each($(this).data("fields"), function(index,fields) {
        var fieldvalue = "";
        if(acquirer_values.hasOwnProperty(fields.key))
            fieldvalue = acquirer_values[fields.key];
        $("#settingsAcquirerModal form").append(addField(fields.key,fields.id,fields.value,fieldvalue));
    });
    $("#settingsAcquirerModal").modal("show");
});

$(".closeModal").on("click",function() {
    $("#settingsAcquirerModal").modal("hide");
});

$("#settingsAcquirerModal .confirm").on("click",function() {
    $.ajax({
        method: "POST",
        url: "?",
        data: {
            acquirer_id: $('#settingsAcquirerModal #acquirer-id').val(),
            acquirer_data: $( "#settingsAcquirerModal form" ).serialize()
        }
    }).done(function (data) {
        $('#settingsAcquirerModal').modal('hide');
        $("button.btnAcquirerSettings[data-id=\"" + $('#settingsAcquirerModal #acquirer-id').val() + "\"]").attr("data-field-values",data);
    });
})

function addField(label,id,text,value) {
    return "<div class=\"form-group stdFormsSetup\" data-label=\"" + label + "\">\n" +
        "<label for=\"" + id + "\" class=\"col-form-label\">" + text + "</label>\n" +
        "<input type=\"text\" class=\"form-control\"  name=\"" + label + "\" id=\"" + id + "\" value=\"" + value + "\">\n" +
        "</div>";
}