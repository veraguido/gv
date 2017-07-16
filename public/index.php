<?php

require_once __DIR__ . '/../vendor/autoload.php';


// DEV MODE
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $app = new Gvera\Gvera();
    $app->run();
} catch(\Exception $e) {
    die($e->getMessage());
}
