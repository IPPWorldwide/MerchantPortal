<?php
function foot() {
    global $inline_css, $inline_script,$load_script,$plugins,$IPP_CONFIG;
    $css = "";
    $script = "";
    if(file_exists("dashboard.js"))
        $load_script[] = "dashboard.js";
    foreach($inline_css as $value) {
        $css .= "<style>".$value."</style>";
    }
    foreach($inline_script as $value) {
        $script .= "<script>".$value."</script>";
    }
    foreach($load_script as $value) {
        $script .= "<script src='".$value."'></script>";
    }
    if(!is_null($plugins->hook_footer)) {
        foreach ($plugins->hook_footer as $value) {
            $script .= $value;
        }
    }
    return '    </main>
  </div>
</div>

    <script src="'.BASEDIR.'assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
    <script src="'.BASEDIR.'assets/js/array2excel.js"></script>
    '.$css.''.$script.'
    <script>
    $(function() {
        function log(message) {
            console.log(message);
        }';
        if(!isset($IPP_CONFIG["PORTAL_DEACTIVATE_SEARCH"]) || (isset($IPP_CONFIG["PORTAL_DEACTIVATE_SEARCH"]) && !$IPP_CONFIG["PORTAL_DEACTIVATE_SEARCH"])){

        echo '$("#CustomerSearch").autocomplete({
            html:true,
            source: function(request, response) {
                $.ajax({
                    url: "'.BASEDIR.'search/",
                    dataType: "jsonp",
                    data: {
                        q: request.term
                    },
                    success: function(data) {
                        console.log(data);
                        response(data);
                    }
                });
            },
            search: function(event, ui) {
                $("#CustomerSearchResults").empty();
            },
            response: function(event, ui) {
              if(ui.content.length == 0) {
                $(\'#autocomplete-suggestion\').html(\'No matches found.\');
              }
            else {
                //console.log(ui.content);
            }
            },
            
            minLength: 3,
            select: function(event, ui) {
                log(ui.item ?
                    "Selected: " + ui.item.label :
                    "Nothing selected, input was " + this.value);
            },
            open: function() {
                $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
            },
            close: function() {
                $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
            }
        }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
                        console.log(item);
  return $( "<li>" )
    .data( "ui-autocomplete-item", item )
    .append( "<a href=\''.$IPP_CONFIG["PORTAL_URL"].'/payments/?id=" + item.action_id + "\'>" + item.date + " - " + item.cardno + " - " + item.holder + " " + item.amount + " " + item.currency + "</a>" )
    .appendTo( ul );
};
    });';
};
 echo '</script>
  </body>
</html>
';
}