<?php
namespace Gvera\Controllers;

use Gvera\Helpers\dependencyInjection\DIContainer;
use Gvera\Helpers\http\JSONResponse;
use Gvera\Helpers\http\Response;
use Throwable;

class Doc extends GvController
{
    public function index()
    {
        try {
            $this->mustPassBasicAuthentication();
        } catch (Throwable $e) {
            $this->httpResponse->response(
                new JSONResponse(
                    ['message' => $e->getMessage()],
                    Response::HTTP_RESPONSE_UNAUTHORIZED,
                    Response::BASIC_AUTH_ACCESS_DENIED
                )
            );
            exit;
        }
    }
}
