<?php

class RequestParams
{
    public function getRequestParams($method,$GET=[],$POST=[]) {
        $request_vars = [];
        switch ($method) {
            case 'GET':
                $request_vars = filter_input_array(INPUT_GET);
                if(count($POST)>0)
                    $request_vars = array_merge($request_vars,filter_input_array(INPUT_POST));
                break;
            case 'POST':
                $request_vars = filter_input_array(INPUT_POST);
                if($request_vars === NULL) {
                    $request_vars = json_decode(file_get_contents('php://input'), true);
                }
                if(count($GET)>0)
                    $request_vars = array_merge($request_vars,filter_input_array(INPUT_GET));
                break;
            case 'PUT':
                if (strlen(trim($content = file_get_contents('php://input'))) === 0) {
                    $content = false;
                }
                parse_str($content, $request_vars);
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
