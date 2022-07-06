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
