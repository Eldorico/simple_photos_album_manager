<?php

require_once('db_functions.inc.php');

define('BAD_REQUEST_CODE', 400);
define('FORBIDDEN_CODE', 403);

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

Class Routes {
    static function get_categories(Request $request, Response $response, array $args){
        $json_categories = json_encode(db_get_categories(), true);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->write($json_categories);
    }

    static function create_album(Request $request, Response $response, array $args){
        $params = $request->getParsedBody();

        $check_params_result = expect_parameters(array('albumName', 'category'), $params);
        if(is_string($check_params_result)){
            return err_response($response, $check_params_result, BAD_REQUEST_CODE);
        }

        $result = db_create_album($params['albumName'], $params['category']);
        if(is_array($result) && array_key_exists('error' , $result)){
            return err_response($response, $result['error']['err_msg'], $result['error']['status_code']);
        }

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode(array('albumId' => $result)));
    }
}

function err_response(Response $response, $err_msg, $status_code){
    $response = $response->withStatus($status_code);
    $response->getBody()->write($err_msg);
    return $response;
}

/**
* @description:
* @return: returns true if all parameters are in the parameters's array.
*          if there are too much parameters or not enough, function returns a
*          msg error (string)
*/
function expect_parameters(array $expected, array $parameters){
    if(count($parameters) > count($expected)){
        return "too much parameters\n";
    }

    foreach ($expected as $key => $expected_key) {
        if(!array_key_exists($expected_key, $parameters)){
            return "parameter $expected_key is missing\n";
        }
    }

    return true;
}

?>
