   $(document).ready(function() {

    function updateMenus(section, menus) {
        $.ajax({
            method: "POST",
            url: "?",
            data: {
                menus: menus,
                section: section
            }
        })
            .done(function( msg ) {
            });
    }

    $(".removeItem").click(function () {
        var this_menu = $(this);
        var this_parent = this_menu.parent().parent();
        this_menu.parent().remove();
        var menus = [];
        this_parent.find("li").each(function() {
            menus.push({url:$(this).attr("data-url"), value:$(this).find(".menuitem").html()});
        });
        updateMenus(this_parent.parent().data("menu"),menus);
    })
    $(".add-element").draggable({
        helper: function() {
            return $(this).clone().removeClass("add-element").appendTo(".canvas").css({
                "zIndex": 5
            }).show();
        },
        cursor: "move",
        containment: "document"
    });
    $(".add-element.true").each(function() {
        $(this).draggable({disabled: true}).css("opacity","0.5");
    });
    $(".canvas").droppable({
        accept: ".add-element",
        drop: function(event, ui) {
            if (!ui.draggable.hasClass("dropped")) {
                ui.draggable.draggable({disabled: true}).css("opacity","0.5");
                $(this).find("ul").append($(ui.draggable).clone().removeClass("ui-draggable").removeClass("dropped"));
                var menus = [];
                $(this).find("ul").find("li").each(function() {
                    menus.push({url:$(this).attr("data-url"), value:$(this).find(".menuitem").html()});

                });
                updateMenus($(this).data("menu"),menus);
            }
        }
    }).sortable({
        placeholder: "sort-placer",
        cursor: "move",
        helper: function (evt, ui) {
            return $(ui).clone().appendTo(".canvas ul").show();
        }
    });
});
