(function () {
    'use strict'
})();
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
                swal({
                    title: "Success!",
                    text: "Updated",
                    icon: "success",
                });
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
    });
    $(".SaveMenu").on("click", function() {
        var menus = [];
        $("body").find("#list").find("div.add-element").each(function() {
            menus.push({url:$(this).attr("data-url"), value:$(this).attr("data-value")});
        });
        updateMenus($(this).attr("data-type"),menus);
    });
    $("#newElementsToMenu").on("submit", function(e) {
        e.preventDefault();
        var ethis = $("#newElementsToMenu input:submit");
        $('#newElementsToMenu input:checked').each(function () {
            addNewMenu(ethis.attr("data-theme"), $(this).attr("value"), $(this).attr("name"));
            $(this).prop( "checked", false );
        });
    });
});

function addNewMenu(theme, key, value) {
    $("#list").append("<div class='add-element draggable' data-url='" + key + "' data-value='" + value + "'><div class='removeItem'><img src='" + theme + "/assets/img/48/remove_icon.png'></div>" + value + "</div>");
}


document.addEventListener('DOMContentLoaded', function () {
    // Query the list element
    const list = document.getElementById('list');

    let draggingEle;
    let placeholder;
    let isDraggingStarted = false;

    // The current position of mouse relative to the dragging element
    let x = 0;
    let y = 0;

    // Swap two nodes
    const swap = function (nodeA, nodeB) {
        const parentA = nodeA.parentNode;
        const siblingA = nodeA.nextSibling === nodeB ? nodeA : nodeA.nextSibling;

        // Move `nodeA` to before the `nodeB`
        nodeB.parentNode.insertBefore(nodeA, nodeB);

        // Move `nodeB` to before the sibling of `nodeA`
        parentA.insertBefore(nodeB, siblingA);
    };

    // Check if `nodeA` is above `nodeB`
    const isAbove = function (nodeA, nodeB) {
        // Get the bounding rectangle of nodes
        const rectA = nodeA.getBoundingClientRect();
        const rectB = nodeB.getBoundingClientRect();

        return rectA.top + rectA.height / 2 < rectB.top + rectB.height / 2;
    };

    const mouseDownHandler = function (e) {
        draggingEle = e.target;

        // Calculate the mouse position
        const rect = draggingEle.getBoundingClientRect();
        x = e.pageX - rect.left;
        y = e.pageY - rect.top;

        // Attach the listeners to `document`
        document.addEventListener('mousemove', mouseMoveHandler);
        document.addEventListener('mouseup', mouseUpHandler);
    };

    const mouseMoveHandler = function (e) {
        const draggingRect = draggingEle.getBoundingClientRect();

        if (!isDraggingStarted) {
            isDraggingStarted = true;

            // Let the placeholder take the height of dragging element
            // So the next element won't move up
            placeholder = document.createElement('div');
            placeholder.classList.add('placeholder');
            draggingEle.parentNode.insertBefore(placeholder, draggingEle.nextSibling);
            placeholder.style.height = `${draggingRect.height}px`;
        }

        // Set position for dragging element
        draggingEle.style.position = 'absolute';
        draggingEle.style.top = `${e.pageY - y}px`;
        draggingEle.style.left = `${e.pageX - x}px`;

        // The current order
        // prevEle
        // draggingEle
        // placeholder
        // nextEle
        const prevEle = draggingEle.previousElementSibling;
        const nextEle = placeholder.nextElementSibling;

        // The dragging element is above the previous element
        // User moves the dragging element to the top
        if (prevEle && isAbove(draggingEle, prevEle)) {
            // The current order    -> The new order
            // prevEle              -> placeholder
            // draggingEle          -> draggingEle
            // placeholder          -> prevEle
            swap(placeholder, draggingEle);
            swap(placeholder, prevEle);
            return;
        }

        // The dragging element is below the next element
        // User moves the dragging element to the bottom
        if (nextEle && isAbove(nextEle, draggingEle)) {
            // The current order    -> The new order
            // draggingEle          -> nextEle
            // placeholder          -> placeholder
            // nextEle              -> draggingEle
            swap(nextEle, placeholder);
            swap(nextEle, draggingEle);
        }
    };

    const mouseUpHandler = function () {
        // Remove the placeholder
        placeholder && placeholder.parentNode.removeChild(placeholder);

        draggingEle.style.removeProperty('top');
        draggingEle.style.removeProperty('left');
        draggingEle.style.removeProperty('position');

        x = null;
        y = null;
        draggingEle = null;
        isDraggingStarted = false;

        // Remove the handlers of `mousemove` and `mouseup`
        document.removeEventListener('mousemove', mouseMoveHandler);
        document.removeEventListener('mouseup', mouseUpHandler);
    };

    // Query all items
    [].slice.call(list.querySelectorAll('.draggable')).forEach(function (item) {
        item.addEventListener('mousedown', mouseDownHandler);
    });
});

