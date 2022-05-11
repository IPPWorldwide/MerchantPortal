<?php
include("../b.php");
if(isset($REQ["method"])) {
    if($REQ["method"] == "add")
        $partner->AddCommunicationTemplate($REQ["hook"],$REQ["type"],$REQ["title"],$REQ["content"],$REQ["receiver"],$REQ["active"]);
    else
        $data = $partner->UpdateCommunicationTemplate($REQ["template_id"],$REQ["hook"],$REQ["type"],$REQ["title"],$REQ["content"],$REQ["receiver"],$REQ["active"]);

    header("Location: /partner/communications");
    die();
}
if(isset($REQ["template_id"])) {
    $template_data = $partner->CommuncationTemplateData($REQ["template_id"]);
    $btn_txt = "Update Template";
    $template_id = $REQ["template_id"];
    $hook    = $template_data->hook;
    $type    = $template_data->type;
    $title   = $template_data->title;
    $content = $template_data->content;
    $active  = $template_data->active;
    $receiver= $template_data->receiver;
    $method  = "update";
} else {
    $template_id  = 0;
    $btn_txt    = "Create Template";
    $hook       = "";
    $type       = "";
    $title      = "";
    $content    = "";
    $active     = 1;
    $receiver   = "";
    $method     = "add";
}

echo head();
echo '
        <form action="?" method="POST" class="form">
            <input type="hidden" name="method" value="'.$method.'">
            <input type="hidden" name="template_id" value="'.$template_id.'">
            <h2>Communication Template</h2>
            <div class="row row-cols-md-1 mb-1">
                <div class="col themed-grid-col">Communication Hook:<br /><input name="hook" class="form-control" value="'.$hook.'"></div>
            </div>
            <div class="row row-cols-md-1 mb-1">
                <div class="col themed-grid-col">Type:<br /><input name="type" class="form-control" value="'.$type.'"></div>
                <div class="col themed-grid-col">Title:<br /><input name="title" class="form-control" value="'.$title.'"></div>
                <div class="col themed-grid-col">Content:<br /><textarea class="form-control" name="content">'.$content.'</textarea></div>
                <div class="col themed-grid-col">Active:<br /><input name="active" class="form-control" value="'.$active.'"></div>
                <div class="col themed-grid-col">Receiver:<br /><input name="receiver" class="form-control" value="'.$receiver.'"></div>
            </div>
            <div class="row row-cols-md-1 mb-1">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mb-3">'.$btn_txt.'</button>
                </div>
            </div>
        </form>
';
echo foot();