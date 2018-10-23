<?php

require_once __DIR__ . '/vendor/autoload.php';
include_once __DIR__ . '/config/locale_setup.php';

// DEV MODE

$http = new swoole_http_server("localhost", 80);

$http->on("start", function ($server) {
    echo "Swoole http server is started at localhost\n";
});

$http->on("request", function ($request, $response) {

    // DEV MODE
    $config = new Gvera\Helpers\config\Config();
    $isDevMode = $config->getConfig('devmode');
    ini_set('display_errors', $isDevMode);
    ini_set('display_startup_errors', $isDevMode);
    $reporting = (true === $isDevMode) ? E_ALL : 0;
    error_reporting($reporting);


    try {
        $app = new Gvera\Gvera($request, $response);
        $app->run($isDevMode);
    } catch(\Throwable $e) {
        $app->handleThrowable($e, $isDevMode);
    }

    
    
});

$http->start();