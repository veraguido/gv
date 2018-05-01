<?php

require_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../config/locale_setup.php';


// DEV MODE
$config = new Gvera\Helpers\config\Config();
$isDevMode = $config->getConfig('devmode');
ini_set('display_errors', $isDevMode);
ini_set('display_startup_errors', $isDevMode);
if ($isDevMode) {
    error_reporting(E_ALL);
}

try {
    $app = new Gvera\Gvera();
    $app->run();
} catch(\Throwable $e) {
    $app->handleException($e, $isDevMode);
}
