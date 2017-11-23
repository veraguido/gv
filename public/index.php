<?php

require_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../config/locale_setup.php';


// DEV MODE
$isDevMode = Gvera\Helpers\config\Config::getInstance()->getConfig('devmode');
ini_set('display_errors', $isDevMode);
ini_set('display_startup_errors', $isDevMode);
if ($isDevMode) {
    error_reporting(E_ALL);
}

try {
    $app = new Gvera\Gvera();
    $app->run();
} catch(\Exception $e) {
    die($e->getMessage());
}
