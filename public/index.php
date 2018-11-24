<?php

require_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../config/locale_setup.php';

// DEV MODE
$config = new Gvera\Helpers\config\Config();
$isDevMode = $config->getConfig('devmode');
ini_set('display_errors', $isDevMode);
ini_set('display_startup_errors', $isDevMode);
$reporting = (true === $isDevMode) ? E_ALL : 0;
error_reporting($reporting);

$diContainer = initializeDIContainer();

try {
    $app = new Gvera\Gvera($diContainer);
    $app->run($isDevMode);
} catch(\Throwable $e) {
    $app->handleThrowable($e, $isDevMode);
}
