<?php

class RequestParams
{
    public function getRequestParams($method) {
        $request_vars = [];
        switch ($method) {
            case 'GET':
                $request_vars = filter_input_array(INPUT_GET);
                break;
            case 'POST':
                $request_vars = filter_input_array(INPUT_POST);
                break;
            case 'PUT':
            case 'DELETE':
                if (strlen(trim($content = file_get_contents('php://input'))) === 0) {
                    $content = false;
                }
                parse_str($content, $request_vars);
                break;
        }
        return $request_vars;
    }
    public function ParamsExist($Params) {
        return isset($Params) ? $Params : "";
    }
}
