<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require 'vendor/autoload.php';

require_once('include/conf.inc.php');
require_once('include/db_functions.inc.php');
require_once('include/functions.inc.php');

$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);

$app->get('/categories', 'Routes::get_categories');
$app->post('/albums', 'Routes::create_album');
$app->post('/images/{albumId}', 'Routes::upload_image');

$app->run();
?>
