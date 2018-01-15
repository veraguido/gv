<?php

namespace Gvera\Events;

class ForgotPasswordCreatedEvent extends Event
{
    const FORGOT_PASSWORD_EVENT = 'forgot_pass_event';

    private $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function getEmail() {
        return $this->email;
    }
}
