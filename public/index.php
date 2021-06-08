<?php

use Gvera\Gv;
use Gvera\Helpers\bootstrap\Bootstrap;

require_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../config/locale_setup.php';
ob_start();

define("CONFIG_ROOT", $_SERVER['DOCUMENT_ROOT'].'/../config/');

$bootstrap = new Bootstrap();
$diContainer = $bootstrap->getDiContainer();
$app = new Gv($diContainer);

// DEV MODE
$isDevMode = $bootstrap->isDevMode();
ini_set('display_errors', $isDevMode);
ini_set('display_startup_errors', $isDevMode);
$reporting = (true === $isDevMode) ? E_ALL : 0;
error_reporting($reporting);


try {
    $app->run();
} catch (Throwable $e) {
    $app->handleThrowable($e, $isDevMode);
}
