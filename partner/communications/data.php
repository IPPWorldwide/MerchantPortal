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
$communication_types = ["email","webhook"];
$receiver_types = ["company","partner"];
$hooks = ["pay_by_link","merchant_creation","payment_completed","forgot_password","report_payments"];
echo head();
echo '
        <form action="?" method="POST" class="form">
            <input type="hidden" name="method" value="'.$method.'">
            <input type="hidden" name="template_id" value="'.$template_id.'">
            <h2>'.$lang["PARTNER"]["OUTBOUND_COMMUNICATION_ADD"]["HEADER"].'</h2>
            <div class="row row-cols-md-1 mb-1">
                <div class="col themed-grid-col">'.$lang["PARTNER"]["OUTBOUND_COMMUNICATION_ADD"]["HOOK"].'<br />
                <select name="hook" class="form-control"></option>'; foreach($hooks as $value) { echo "<option"; if($hook === $value) { echo " selected"; } echo ">".$value."</option>"; } echo '</select></div>
            </div>
            <div class="row row-cols-md-1 mb-1">
                <div class="col themed-grid-col">'.$lang["PARTNER"]["OUTBOUND_COMMUNICATION_ADD"]["TYPE"].'<br />
                <select class="form-control" name="type">
                ';
                foreach($communication_types as $value) {
                    echo "<option value='$value' "; if($value == $type) { echo "selected"; } echo ">".$value."</option>";
                }
                echo '
                </select></div>
                <div class="col themed-grid-col">'.$lang["PARTNER"]["OUTBOUND_COMMUNICATION_ADD"]["TITLE"].'<br /><input name="title" class="form-control" value="'.$title.'"></div>
                <div class="col themed-grid-col">'.$lang["PARTNER"]["OUTBOUND_COMMUNICATION_ADD"]["CONTENT"].'<br /><textarea class="form-control" name="content">'.$content.'</textarea></div>
                <div class="col themed-grid-col">'.$lang["PARTNER"]["OUTBOUND_COMMUNICATION_ADD"]["ACTIVE"].'<br /><input name="active" class="form-control" value="'.$active.'"></div>
                <div class="col themed-grid-col">'.$lang["PARTNER"]["OUTBOUND_COMMUNICATION_ADD"]["RECEIVER"].'<br />
                <select class="form-control" name="receiver">
                ';
                foreach($receiver_types as $value) {
                    echo "<option value='$value' "; if($value == $type) { echo "selected"; } echo ">".$value."</option>";
                }
                echo '</select></div>
            </div>
            <div class="row row-cols-md-1 mb-1">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mb-3">'.$btn_txt.'</button>
                </div>
            </div>
        </form>
';
echo foot();