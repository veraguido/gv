<?php

namespace Gvera\Commands;


use Gvera\Events\UserLoggedInEvent;
use Gvera\Helpers\events\EventDispatcher;
use Gvera\Services\UserService;

class LoginCommand implements ICommand
{
    private $username;
    private $password;
    private $role;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
    }

    public function execute()
    {
        $userService = new UserService();
        $userService->login($this->username, $this->password);

        if (UserService::isUserLoggedIn()) {
            EventDispatcher::dispatchEvent(
                UserLoggedInEvent::USER_LOGGED_IN_EVENT,
                new UserLoggedInEvent(UserLoggedInEvent::USER_LOGGED_IN_EVENT));
        }
    }
}