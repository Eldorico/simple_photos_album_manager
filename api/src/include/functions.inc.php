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

        // save the file in order to work with
        $extension = pathinfo($files['image']->getClientFilename(), PATHINFO_EXTENSION);
        $base_img_name = get_new_img_name($extension);
        $base_img_path = IMG_FOLDER . "/$base_img_name";
        $files['image']->moveTo($base_img_path);

        // convert the file to jpg
        $jpg_file = convert_to_jpg($base_img_path, IMG_FOLDER);
        if(is_array($jpg_file) && array_key_exists('error' , $jpg_file)){
            unlink($base_img_path);
            return err_response($response, $jpg_file['error']['err_msg'], SERVER_ERROR_CODE);
        }

        // resize the file
        $final_img_path = resize_jpg($jpg_file, MAX_PIXELS_PER_IMAGE, IMG_FOLDER);
        if(is_array($jpg_file) && array_key_exists('error' , $final_img_path)){
            unlink($base_img_path);
            unlink($final_img_path);
            return err_response($response, $jpg_file['error']['err_msg'], SERVER_ERROR_CODE);
        }

        // create the miniature
        $miniature_img_path = resize_jpg($jpg_file, MAX_PIXELS_PER_MINIATURE, MINIATURE_FOLDER);
        if(is_array($miniature_img_path) && array_key_exists('error' , $miniature_img_path)){
            unlink($base_img_path);
            unlink($final_img_path);
            return err_response($response, $jpg_file['error']['err_msg'], SERVER_ERROR_CODE);
        }

        // add the final image to the database
        $img_db_id = db_add_img_to_album($final_img_path, $args['albumId']);

        // add the final miniature to the database
        $miniature_db_id = db_link_miniature_to_img($miniature_img_path, $img_db_id);

        // remove the tmp files
        unlink($base_img_path);
        unlink($jpg_file);

        // $response->getBody()->write("final_img_id : $img_db_id\n");
        // $response->getBody()->write("final_miniature_id : $miniature_db_id\n");
        return $response;
    }

    static function get_img_url(Request $request, Response $response, array $args){
        $miniature_param = isset($request->getQueryParams()['miniature']) ? boolval($request->getQueryParams()['miniature']) : false;
        $db_result = db_get_img_path($args['imageId'], $miniature_param);
        if(is_array($db_result) && array_key_exists('error' , $db_result)){
            return err_response($response, $db_result['error'], BAD_REQUEST_CODE);
        }

        $rel_path = str_replace($request->getServerParam('DOCUMENT_ROOT', 'error'), '', $db_result);
        $rel_path = ltrim($rel_path, '/');
        $img_url = $request->getServerParam('REQUEST_SCHEME', 'error') . '://' . $request->getServerParam('HTTP_HOST', 'error') . "/$rel_path" ;

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->write(str_replace('\\', '', json_encode(array('url' => $img_url))));
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

/**
* @return:  returns the img path of the new jpg created. (another "unique" name)
*           if an errors occurs, returns an array("error" => "err_msg")
*/
function convert_to_jpg($img_path, $dst_folder){
    try{
        $img = new Imagick($img_path);
        $converted_file_path = $dst_folder . '/' . get_new_img_name("jpg");
        $result = $img->writeImage($converted_file_path);
        if(!$result){
            return array("error" => "coulnd't convert image to jpg");
        }
        return $converted_file_path;
    }catch(Exception $e){
        return array("error" => "Exception: coulnd't convert image to jpg" . $e->getMessage());
    }

}

/**
* @return: the path (string) of the resized image.
*           if an error occured: returns an array("error" => "err_msg");
* // TODO: add the if needed (if we need to resize the image)
*/
function resize_jpg($img_path, $max_nb_pixels, $dst_folder){
    try{
        $img = new Imagick($img_path);
        $src_width = $img->getImageWidth();
        $src_height = $img->getImageHeight();

        $ratio = floatval($src_width) / floatval($src_height);
        $x = sqrt(floatval($max_nb_pixels) * $ratio);
        $y = $x / $ratio;

        $result = $img->adaptiveResizeImage(intval($x), intval($y), true);
        if(!$result){
            return array("error" => "couldn't resize the image");
        }

        $resized_file_path = $dst_folder . '/' . get_new_img_name("jpg");
        $result = $img->writeImage($resized_file_path);
        if(!$result){
            return array("error" => "coulnd't save iamge after resizing it");
        }

        return $resized_file_path;
    }catch(Exception $e){
        return array("error" => "Exception: coulnd't resize image to jpg" . $e->getMessage());
    }
}

function get_new_img_name($extension){
    return strval(get_current_timestamp()) . '_' . generateRandomString(NB_RANDOM_CHAR_IN_IMG_NAME) . ".$extension";
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
