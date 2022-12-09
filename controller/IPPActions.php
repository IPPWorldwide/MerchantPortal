<?php
class IPPActions {
    protected $actions = [];
    function add_action(string $hook_name, callable $callback, int $priority=25, array $arguments=[]) {
        $this->actions[$hook_name][] = [
            $callback
        ];
    }
    public function get_action(string $hook_name) {
        if(!isset($this->actions[$hook_name]))
            return [];
        else {
            foreach($this->actions[$hook_name] as $value) {
                call_user_func($value[0]);
            }
        }
    }
}
