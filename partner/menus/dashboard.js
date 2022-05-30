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
                //console.log( "Data Saved: " + msg );
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
    $(".canvas").droppable({
        accept: ".add-element",
        drop: function(event, ui) {
            if (!ui.draggable.hasClass("dropped")) {
                $(this).find("ul").append($(ui.draggable).clone().removeClass("ui-draggable").removeClass("dropped"));
                var menus = [];

                console.log($(this));

                $(this).find("ul").find("li").each(function() {
//                    menus[$(this).attr("data-url")] = $(this).html();
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
