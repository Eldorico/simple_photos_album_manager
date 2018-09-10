<?php

require_once('db_functions.inc.php');

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

Class Routes {
    public static function get_categories(Request $request, Response $response, array $args){
        $json_categories = json_encode(db_get_categories(), true);
        $response->getBody()->write($json_categories);
        return $response;
    }
}

?>
