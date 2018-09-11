<?php

define('DB_NAME', 'app_db');
define('DB_USER', 'admin');
define('DB_PASSWORD', 'admin');
define('DB_HOST', '127.0.0.1');

define('MAX_PIXELS_PER_IMAGE', 1215000); // should ensure a jpg image below 1MB
define('NB_RANDOM_CHAR_IN_IMG_NAME', 4);

define('IMG_FOLDER', realpath(dirname(__FILE__) . '/../img'));
define('MINIATURE_FOLDER', IMG_FOLDER . '/miniatures');

$db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if (!$db) {
    echo("Error: Unable to connect to MySQL." . PHP_EOL);
    echo("Debugging errno: " . mysqli_connect_errno() . PHP_EOL);
    echo("Debugging error: " . mysqli_connect_error() . PHP_EOL);
    exit();
}

?>
