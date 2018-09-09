<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require 'vendor/autoload.php';

require_once('include/conf.inc.php');
require_once('include/db_functions.inc.php');

$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {

    $name = $args['name'];
    $response->getBody()->write("Wesh mec $name!<br/>");

    $categories = get_categories();
    foreach ($categories as $key => $value) {
        $response->getBody()->write("$value<br/>");
    }

    return $response;
});
$app->run();

?>
