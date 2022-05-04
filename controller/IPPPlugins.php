<?php


class IPPPlugins
{
    public $available_plugins;
    public $hook_footer;
    public $hook_header;

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
    }
    public function getSettingsValues($plugin_name, $value) {
        if(isset($this->values[$value]))
            return $this->values[$value];
        elseif(isset($this->available_plugins[$plugin_name]->values))
            return json_encode($this->available_plugins[$plugin_name]->values);
        else
            return "";
    }
    public function getId($plugin_name) {
        return $this->available_plugins[$plugin_name]->id;
    }
    private function loadPlugin($plugin_name) {
        $this->available_plugins[$plugin_name] = new $plugin_name();
    }
}
