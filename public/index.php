<?php

require_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../config/locale_setup.php';
ob_start();


$bootstrap = new \Gvera\Helpers\bootstrap\Bootstrap();
$diContainer = $bootstrap->getDiContainer();
$app = new Gvera\Gvera($diContainer);
$isDevMode = $bootstrap->getConfig()->getConfigItem('devmode');

// DEV MODE
ini_set('display_errors', $isDevMode);
ini_set('display_startup_errors', $isDevMode);
$reporting = (true === $isDevMode) ? E_ALL : 0;
error_reporting($reporting);


try {
    $app->run($isDevMode);
} catch (\Throwable $e) {
    $app->handleThrowable($e, $isDevMode);
}
