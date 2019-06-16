<?php


namespace Gvera\Models;


class BasicAuthenticationDetails extends GvModel
{
    private $username;
    private $password;

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
}