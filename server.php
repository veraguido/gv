<?php

require_once __DIR__ . '/vendor/autoload.php';
include_once __DIR__ . '/config/locale_setup.php';

// DEV MODE

$http = new swoole_http_server("localhost", 80);

$http->on("start", function ($server) {
    echo "Swoole http server is started at localhost\n";
});

$static = [
    'css'  => 'text/css',
    'js'   => 'text/javascript',
    'png'  => 'image/png',
    'gif'  => 'image/gif',
    'jpg'  => 'image/jpg',
    'jpeg' => 'image/jpg',
    'mp4'  => 'video/mp4',
    'ico'  => 'image/x-icon'
];

$http->on("request", function ($request, $response) use ($static) {

    if (getStaticFile($request, $response, $static)) {
        return;
    }

    // DEV MODE
    $config = new Gvera\Helpers\config\Config();
    $isDevMode = $config->getConfig('devmode');
    ini_set('display_errors', $isDevMode);
    ini_set('display_startup_errors', $isDevMode);
    $reporting = (true === $isDevMode) ? E_ALL : 0;
    error_reporting($reporting);


    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (isset($request->cookie[session_name()])) {
        // Client has session cookie set, but Swoole might have session_id() from some
        // other request, so we need to regenerate it
        session_id($request->cookie[session_name()]);
    } else {
        $params = session_get_cookie_params();

        if (session_id()) {
            session_id(\bin2hex(\random_bytes(32)));
        }
        $_SESSION = [];

        $response->rawcookie(
            session_name(),
            session_id(),
            $params['lifetime'] ? time() + $params['lifetime'] : null,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    $_SESSION['key'] = $_SESSION['key'] ?? rand();

    try {
        $app = new Gvera\Gvera($request, $response);
        $app->run($isDevMode);
    } catch(\Throwable $e) {
        $app->handleThrowable($e, $isDevMode);
    }
    
});

function getStaticFile(swoole_http_request $request, swoole_http_response $response, array $static) : bool {
    $staticFile = __DIR__ . '/public' . $request->server['request_uri'];
    if (! file_exists($staticFile)) {
        return false;
    }
    $type = pathinfo($staticFile, PATHINFO_EXTENSION);
    if (! isset($static[$type])) {
        return false;
    }
    $response->header('Content-Type', $static[$type]);
    $response->sendfile($staticFile);
    return true;
}

$http->start();