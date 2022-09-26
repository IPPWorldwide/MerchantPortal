(function () {
    'use strict'
    feather.replace({ 'aria-hidden': 'true' })
})();
$(document).ready(function (){
    $('#upload-plugin-file').click(function (){
        var vidFileLength = $("#plugin-file")[0].files.length;
        if(vidFileLength === 0){
            swal({
                title: "Error!",
                text: "Please choose a file!",
                icon: "error",
            });
        }else{
            var fd = new FormData();
            var files = $('#plugin-file')[0].files;
            fd.append('new-plugin',files[0]);
            $.ajax({
                url: 'plugin-validation.php',
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function(response){
                    var d = JSON.parse(response);
                    if(d.error){
                        swal({
                            title: "Error!",
                            text: d.message,
                            icon: "error",
                        });
                    }else{
                        swal({
                            title: "Success!",
                            text: d.message,
                            icon: "success",
                        }).then(function (){
                            window.location.reload();
                        });
                    }
                },
            });
        }
    });
});
$('.installModal').on('click', function () {
    let plugin_div = $(`[data-plugin-id="${$(this).data("plugin-name")}"]`);
    plugin_div.find(".btnShowMore").attr("data-installed","1");
    $.post( "install.php", { plugin: $(this).data("plugin-name"), file: $(this).data("plugin-file") })
        .done(function( data ) {
            if(data === "") {
                $("#pluginViewMoreModal .removeModal").css("display","block");
                $("#pluginViewMoreModal .installModal").css("display","none");
                plugin_div.find(".removeModal").css("display","block");
                plugin_div.find(".installModal").css("display","none");
            }
        });
});
$('.removeModal').on('click', function () {
    let plugin_type = $(this).data("local-plugin");
    let plugin_div = $(`[data-plugin-id="${$(this).data("plugin-name")}"]`);
    var btn2 = $('.plugin-btn-'+$(this).attr("data-plugin-key"));
    plugin_div.find(".btnShowMore").attr("data-installed","0");
    $("#pluginViewMoreModal .pluginSettingsModal").css("display","none");
    $.post( "remove.php", { id: $(this).attr("data-plugin-id"), plugin: $(this).attr("data-plugin-name") })
    .done(function( data ) {
        if(data === "") {
            $("#pluginViewMoreModal .removeModal").css("display","none");
            $("#pluginViewMoreModal .installModal").css("display","block");
            plugin_div.find(".removeModal").css("display","none");
            plugin_div.find(".installModal").css("display","block");
        }
    });
});
$(".btnShowMore").on("click", function() {
    $this = $(this);
    $("#pluginModal .modal-header").html($this.attr("data-name"));
    $("#pluginViewMoreModal #exampleModalLongTitle").html($this.attr("data-name"));
    $("#pluginViewMoreModal .card-body .card-text").html($this.attr("data-description"));
    $("#pluginViewMoreModal .pluginSettingsModal").attr("data-fields", $this.attr("data-fields"));
    $("#pluginViewMoreModal .pluginSettingsModal").attr("data-values", $this.attr("data-values"));
    $("#pluginViewMoreModal .pluginSettingsModal").attr("data-plugin-name", $this.attr("data-slug"));
    $("#pluginViewMoreModal .installModal").attr("data-plugin-file", $this.attr("data-plugin-file"));
    $("#pluginViewMoreModal .installModal").attr("data-plugin-name", $this.attr("data-slug"));
    $("#pluginViewMoreModal .removeModal").attr("data-plugin-name", $this.attr("data-slug"));

    if($this.attr("data-external-login") === "") {
        $("#pluginViewMoreModal .ExternalLogin").css("display","none");
    } else {
        $("#pluginViewMoreModal .ExternalLogin").css("display","block");
        $("#pluginViewMoreModal .ExternalLogin").attr("href",$this.attr("data-external-login"));
    }
    if($this.attr("data-installed") === "1") {
        $("#pluginViewMoreModal .installModal").css("display","none");
        $("#pluginViewMoreModal .removeModal").css("display","block");
        $("#pluginViewMoreModal .pluginSettingsModal").css("display","block");
    } else {
        $("#pluginViewMoreModal .pluginSettingsModal").css("display","none");
        $("#pluginViewMoreModal .removeModal").css("display","none");
        $("#pluginViewMoreModal .installModal").css("display","block");

    }
    $("#pluginViewMoreModal .card-body .config").remove();
    jQuery.each(JSON.parse($this.attr("data-configuration")), function(index, item) {
        console.log(item);
        console.log(item[0]);
        $("#pluginViewMoreModal .pluginSettingsModal").before("<a class=\"btn btn-sm btn-info text-white pluginSettingsModal me-2\" href='/?pages=" + $this.attr("data-slug") + "&page=" + item[0] + "'>" + item[1] + "</a>");
    });
});
$('.pluginSettingsModal').on('click', function () {
    var button = $(this);
    $('#pluginModal').modal('show');
    $('#pluginModal .modal-title').text($(this).data('plugin-title'));
    var plugin_values = JSON.parse(button.attr("data-values"));
    var json_plugin_fields = JSON.parse(button.attr("data-fields"));
    $("#pluginModal form").empty().append('<input type="hidden" id="plugin_slug" name="plugin_slug" value="' + $(this).data("plugin-name") + '"/><input type="hidden" id="plugin_id" name="plugin_id" value="' + plugin_values.plugin_id + '"/>');
    $.each(json_plugin_fields, function(key,value) {
        if(typeof value.standard === 'undefined')
            input_value = "";
        else
            input_value = value.standard;
        if(typeof plugin_values[value.id] !== 'undefined')
            input_value = plugin_values[value.id];
        if(typeof value.type === 'undefined')
            input_type = ["input"];
        else
            input_type = [value.type, value.html];
        $("#pluginModal form").append(plugin_fields(input_type,value.title,value.name,value.id,input_value,value.hidden));
    });
});

function plugin_fields(field_type,text,name,id,value,hidden) {
    var css = "";
    if(typeof hidden !== 'undefined' && hidden === true)
        css = "display:none";
    if(field_type[0] == "html")
        return "<div class=\"form-group\" style='" + css +  "'>" + field_type[1] + "</div>";
    else
        return "<div class=\"form-group\" style='" + css +  "'><label for=\""+id+"\" class=\"col-form-label\">"+text+":</label><input type=\"text\" class=\"form-control\" name=\""+name+"\" id=\""+id+"\" value=\"" + value + "\"></div>";
}
$(".confirmPluginSettngs").on("click", function() {
    $.post( "?", $("#pluginModal form").serialize())
        .done(function( data ) {
            if(data !== "") {
                $(".allplugins").find("[data-plugin-id='" + $("#pluginModal form #plugin_slug").val() + "']").find(".btnShowMore").attr("data-values",data);
                $(".modal.fade").find("[data-plugin-name='" + $("#pluginModal form #plugin_slug").val() + "']").first().attr("data-values",data);
                $('#pluginModal').modal('hide');
            }
        });
});
$("#pluginSettings").on("submit",function(e) {
    e.preventDefault();
    $(".confirmPluginSettngs").click();
});