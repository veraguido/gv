<?php

namespace Gvera\Events;


class UserRegisteredEvent extends Event
{
    const USER_REGISTERED_EVENT  = 'user_registered_event';
    protected $username;
    protected $email;

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function __construct($name, $username, $email)
    {
        parent::__construct($name);
        $this->username = $username;
        $this->email = $email;
    }
}