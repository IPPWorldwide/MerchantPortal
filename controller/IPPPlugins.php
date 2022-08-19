<?php
class IPPPlugins
{
    public $available_plugins;
    public $hook_footer;
    public $hook_header;
    public $hook_login;
    public $hook_onboarding;
    public $bookkeeping;
    public $communication;

    public function loadPlugins() {
        if ($handle = opendir(BASEDIR . 'plugins')) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && $entry != "index.php") {
                    include(BASEDIR . "plugins/".$entry."/init.php");
                    $this->loadPlugin($entry);
                    if(file_exists(BASEDIR . "plugins/".$entry."/settings.php")) {
                        $settings = [];
                        include(BASEDIR . "plugins/".$entry."/settings.php");
                        if(isset($settings) && count($settings) > 0)
                            $this->setSettingsValues($entry,$settings);
                    }
                }
            }
            closedir($handle);
        }
    }

    public function setAvailablePlugin($slug) {
        $this->available_plugins[] = $slug;
    }

    public function setBookeeping($slug) {
        $this->bookkeeping[] = $slug;
    }

    public function getAvailablePlugins() {
        return $this->available_plugins;
    }
    public function plugin_name()
    {
        $this->name();
    }
    public function init()
    {
        if(class_exists($this->initialization()))
            $this->initialization();
    }
    protected function name()
    {
        echo 'The name of each plugin<br />';
    }
    protected function initialization()
    {
        echo 'The plugin initialization<br />';
    }
    public function fields()
    {
        if($this->getFields())
            return $this->getFields();
    }
    public function GetPluginFields($entry)
    {
        $settings = [];
        if(file_exists(BASEDIR . "plugins/".$entry."/settings.php")) {
            include(BASEDIR . "plugins/".$entry."/settings.php");
        }
        return $settings;
    }
    public function getSettingsFields($plugin_name) {
        if(isset($this->available_plugins[$plugin_name])) {
            return json_encode($this->available_plugins[$plugin_name]->getFields());
        }
        else {
            return "";
        }
    }
    private function setSettingsValues($plugin_name,$values) {
        if(is_object($this->available_plugins[$plugin_name])) {
            $this->available_plugins[$plugin_name]->values = $values;
        }
        if(method_exists($this->available_plugins[$plugin_name],"hook_footer"))
            $this->hook_footer[] = $this->available_plugins[$plugin_name]->hook_footer();
        if(method_exists($this->available_plugins[$plugin_name],"hook_header"))
            $this->hook_header[] = $this->available_plugins[$plugin_name]->hook_header();
        if(method_exists($this->available_plugins[$plugin_name],"hook_login"))
            $this->hook_login[] = $this->available_plugins[$plugin_name]->hook_login();
    }
    public function updateSettingsValues($plugin_slug,$variable,$content,$action="o") {
        global $partner,$utils;
        $fields = $this->GetPluginFields($plugin_slug);
        if(!isset($fields["plugin_id"])) {
            echo "An unexpected error. Could not identify plugin_id";
            die();
        }
        if(is_array($content) || is_object($content))
            $content = json_encode($content,true);

        if(!isset($fields[$variable]) || $action === "o") {
            $fields[$variable] = $content;
        }
        elseif(isset($fields[$variable]) && $action === "a") {
            if($utils->isJson($content)) {
                $old_fields = json_decode($fields[$variable],false);
                $old_fields[] = (json_decode($content)[0]);
                $fields[$variable] = json_encode($old_fields);
            } else {
                $fields[$variable] .= $content;
            }
        }
        $myfile = fopen(BASEDIR . "plugins/".$plugin_slug."/settings.php", "w") or die("Unable to open file!");
        $txt = "<?php\n";
        fwrite($myfile, $txt);
        foreach($fields as $key=>$value) {
            $partner->UpdatePluginSettings($fields["plugin_id"],$key,$value);
            $txt = "\$settings[\"".$key."\"] = '" . $value . "';\n";
            fwrite($myfile, $txt);
        }
        fclose($myfile);
        $update_plugin = new $plugin_slug();
        if(method_exists($update_plugin,"hookUpdate"))
            $update_plugin->hookUpdate($plugin_slug,$fields["plugin_id"],$fields);

    }
    public function getSettingsValues($plugin_name, $value) {
        if(isset($this->values[$value]))
            return $this->values[$value];
        elseif(isset($this->available_plugins[$plugin_name]->values[$value]))
            return $this->available_plugins[$plugin_name]->values[$value];
        elseif(isset($this->available_plugins[$plugin_name]->values))
            return json_encode($this->available_plugins[$plugin_name]->values);
        else
            return "{}";
    }
    public function getId($plugin_name) {
        return $this->available_plugins[$plugin_name]->id;
    }
    public function getStandardConfigs($plugin_name) {
        $this->setFields();
        $standard_values = [];
        if(is_object($this->fields()) || is_array($this->fields())) {
            foreach($this->fields() as $value) {
                if(isset($value["standard"]))
                    $standard_values[] = $value;
            }
        }
        return $standard_values;
    }
    public function hasExternalLogin($plugin_name) {
        if(
            isset($this->available_plugins[$plugin_name]) &&
            is_object($this->available_plugins[$plugin_name]) &&
            method_exists($this->available_plugins[$plugin_name],"externalLogin"))
            return $this->available_plugins[$plugin_name]->externalLogin();
        else
            return false;

    }
    public function hasExternalCommunication($plugin_name,$method,$request) {
        if(
            isset($this->available_plugins[$plugin_name]) &&
            is_object($this->available_plugins[$plugin_name]) &&
            method_exists($this->available_plugins[$plugin_name],"externalFeedback"))
            return (array)$this->available_plugins[$plugin_name]->externalFeedback($method,$request);
        else
            return [];
    }
    private function loadPlugin($plugin_name) {
        $this->available_plugins[$plugin_name] = new $plugin_name();

        if(isset($this->available_plugins[$plugin_name]->bookkeeping))
            $this->bookkeeping = $this->available_plugins[$plugin_name]->bookkeeping;

        if(isset($this->available_plugins[$plugin_name]->hook_onboarding))
            $this->hook_onboarding = $this->available_plugins[$plugin_name]->hook_onboarding;

        if(isset($this->available_plugins[$plugin_name]->communication))
            $this->communication = $this->available_plugins[$plugin_name]->communication;

    }



    public function loadPage($plugin_name,$page,$REQ) {
        $this->available_plugins[$plugin_name] = new $plugin_name();
        return (array)$this->available_plugins[$plugin_name]->{"pages_".$page}($REQ);
    }








    // BOOKEEPING
    public function ListInvoices() {
        $invoice_lists = new stdClass();
        if(is_array($this->bookkeeping)) {
            foreach($this->bookkeeping as $value) {
                $bookkeeping = new $value();
                $invoices = json_decode(json_encode($bookkeeping->getInvoices()));
                foreach($invoices as $invoice) {
                    $invoice_lists->{$invoice->guid} = $invoice;
                }
            }
        }

        return $invoice_lists;
    }

    public function ListSpecificInvoice($provider,$guid) {
        $bookkeeping = new $provider();
        $data = $bookkeeping->getInvoice($guid);

        return $data;
    }


}
