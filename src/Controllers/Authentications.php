<?php

namespace Gvera\Controllers;

use Gvera\Helpers\http\JSONResponse;
use Gvera\Helpers\http\Response;
use Gvera\Models\User;
use Gvera\Services\JWTService;

class Authentications extends GvController
{
    /**
     * @httpMethod("POST")
     */
    public function jwt()
    {
        $username = $this->httpRequest->getParameter('username');
        $password = $this->httpRequest->getParameter('password');

        $user = $this->getEntityManager()->getRepository(User::class)->findOneBy(['username' => $username]);
        $userService = $this->getUserService();
        if (null === $user || !$userService->validatePassword($password, $user->getPassword())) {
            $this->httpResponse->response(
                new JSONResponse(
                    [],
                    Response::HTTP_RESPONSE_UNAUTHORIZED,
                    Response::BEARER_AUTH_ACCESS_DENIED
                )
            );
            return;
        }

        $service = $this->getJwtService();
        $this->httpResponse->response(new JSONResponse(['token' => $service->createToken($user)]));
    }
}
