<?php

require_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../config/locale_setup.php';



$bootstrap = new Bootstrap();
$diContainer = $bootstrap->getDiContainer();
$app = new Gvera\Gvera($diContainer);
$isDevMode = $bootstrap->getConfig()->getConfig('devmode');

// DEV MODE
ini_set('display_errors', $isDevMode);
ini_set('display_startup_errors', $isDevMode);
$reporting = (true === $isDevMode) ? E_ALL : 0;
error_reporting($reporting);

try {
    $app = new Gvera\Gvera($diContainer);
    $app->run($isDevMode);
} catch(\Throwable $e) {
    $app->handleThrowable($e, $isDevMode);
}
