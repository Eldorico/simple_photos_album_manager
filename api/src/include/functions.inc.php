<?php

require_once('db_functions.inc.php');

define('BAD_REQUEST_CODE', 400);
define('FORBIDDEN_CODE', 403);
define('SERVER_ERROR_CODE', 500);

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

    static function upload_image(Request $request, Response $response, array $args){
        if(!db_album_exists_from_id($args['albumId'])){
            return err_response($response, "Album doesnt exists", FORBIDDEN_CODE);
        }

        // check for a file
        $files = $request->getUploadedFiles();
        if(empty($files['image'])) {
            return err_response($response, "Expected a file in body as : image=@file", BAD_REQUEST_CODE);
        }
        if($files['image']->getError() !== UPLOAD_ERR_OK){
            return err_response($response, "An error was encountered uploading the file", SERVER_ERROR_CODE);
        }

        // debug
        echo(IMG_FOLDER);

        // save the file
        $extension = pathinfo($files['image']->getClientFilename(), PATHINFO_EXTENSION);
        $final_img_name = strval(get_current_timestamp()) . '_' . generateRandomString(3) . ".$extension";
        $files['image']->moveTo(IMG_FOLDER . "/$final_img_name");
        // $files['image']['name'] = $final_img_name;
        // var_dump($files['image']);



        // debug
        // var_dump($args);
        // $response->getBody()->write("album exists: ".db_album_exists_from_id($args['albumId'])."\n");
        $response->getBody()->write($final_img_name);
        return $response;
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

function get_current_timestamp(){
    $date = new DateTime();
    return $date->getTimeStamp();
}

// https://stackoverflow.com/questions/4356289/php-random-string-generator
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>
