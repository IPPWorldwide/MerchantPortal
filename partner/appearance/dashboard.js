$('.purchaseModal').on('click', function () {
    var btn = $(this);
    $("#purchaseModal h5.modal-title").html(btn.attr("data-theme-name"));
    $("#purchaseModal .modal-header .monthly_price").html(btn.attr("data-theme-monthly-cost"));
    $("#purchaseModal .modal-body").html(btn.parent().parent().parent().find(".card-text").attr("data-description"));
    $("#purchaseModal .modal-footer .confirmPurchase").attr("data-slug",btn.attr("data-slug"));
    $("#purchaseModal").modal("show");
});
$(".confirmPurchase").on("click", function() {
    $.post( "install.php", { themes: $(this).attr("data-slug") })
        .done(function( data ) {
            swal({
                title: "Success!",
                text: "",
                icon: "success",
            }).then(function (){
                window.location.reload();
            });
        });
});
$('.installModal').on('click', function () {
    var btn = $(this);
    $.post( "activate.php", { themes: $(this).data("plugin-name") })
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
$(".btnSettings").on("click",function() {
    $("#appearanceSettingsModal").modal("show");
});

$("#appearanceSettingsModal .closeModal").on("click",function() {
    $("#appearanceSettingsModal").modal("hide");
});
$("#appearanceSettingsModal .confirm").on("click",function() {
    $.ajax({
        url: '?',
        data: $("#appearanceSettingsModal form").serialize(),
        cache: false,
        method: "POST",
        success: function (data) {
            $("#appearanceSettingsModal").modal("hide");
        }
    });
});
