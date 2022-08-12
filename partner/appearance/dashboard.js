(function () {
    'use strict'
    feather.replace({ 'aria-hidden': 'true' })
})();
$('.installModal').on('click', function () {
var btn = $(this);
$.post( "install.php", { plugin: $(this).data("plugin-name"), file: $(this).data("plugin-file") })
    .done(function( data ) {
        $(".btnHidden").removeClass("btnHidden");
        btn.addClass("btnHidden");
    });
});
$('.removeTheme').on('click', function () {
    var btn = $(this);
    $.post("remove.php", {plugin: $(this).data("plugin-name"), file: $(this).data("plugin-file")})
        .done(function (data) {
            $("#theme_" + btn.data("plugin-name")).remove();
        });
});

$(document).ready(function (){
    $('#upload-theme-file').click(function (){
        var vidFileLength = $("#theme-file")[0].files.length;
        if(vidFileLength === 0){
            swal({
                title: "Error!",
                text: "Please choose a file!",
                icon: "error",
            });
        }else{
            var fd = new FormData();
            var files = $('#theme-file')[0].files;
            fd.append('new-theme',files[0]);
            $.ajax({
                url: 'apperance-validation.php',
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