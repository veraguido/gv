<?php

namespace Gvera\Helpers\security;

class CSRFToken
{
    const ID = 'csrf_token';
    private $token;

    /**
     * @return mixed
     */
    public function getTokenValue()
    {
        return $this->token;
    }

    /**
     * CSRFToken constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }
}
