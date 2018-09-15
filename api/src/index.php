<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require 'vendor/autoload.php';

require_once('include/conf.inc.php');
require_once('include/db_functions.inc.php');
require_once('include/functions.inc.php');

$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);

$app->get('/categories', 'Routes::get_categories');
$app->get('/albums', 'Routes::get_albums');
$app->post('/albums', 'Routes::create_album');
$app->get('/images/singleImage/{imageId}', 'Routes::get_img_url');
$app->post('/images/{albumId}', 'Routes::upload_image');


// CORS rules for developpement
// TODO: remove this (or update it) for production!
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});



$app->run();
?>
