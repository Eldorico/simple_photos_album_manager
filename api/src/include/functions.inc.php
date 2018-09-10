<?php

require_once('db_functions.inc.php');

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

Class Routes {
    static function get_categories(Request $request, Response $response, array $args){
        $json_categories = json_encode(db_get_categories(), true);
        $response->getBody()->write($json_categories);
        return $response;
    }

    static function create_album(Request $request, Response $response, array $args){
        $check_params_result = expect_parameters(array('albumName', 'category'), $request->getParsedBody());
        if(is_string($check_params_result)){
            return return_bad_request($response, $check_params_result);
        }
    }
}

function return_bad_request(Response $response, $err_msg){
    $response = $response->withStatus(400);
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
