<?php

namespace Gvera\Events;


class UserRegisteredEvent extends Event
{
    const USER_REGISTERED_EVENT  = 'user_registered_event';
    protected $name;
    protected $email;

    /**
     * @return string
     */
    public function getName(): string
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

    public function __construct(string $name, $username, $email)
    {
        parent::__construct($name);
        $this->name = $name;
        $this->email = $email;
    }
}