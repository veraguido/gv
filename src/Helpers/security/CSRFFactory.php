<?php

namespace Gvera\Helpers\security;

class CSRFFactory
{
    /**
     * @return string
     * @throws \Exception
     */
    public function createToken(): CSRFToken
    {
        $token = bin2hex(random_bytes(32));
        return new CSRFToken($token);
    }
}
