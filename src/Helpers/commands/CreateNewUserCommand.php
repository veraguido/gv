<?php
/**
 * Created by PhpStorm.
 * User: guido
 * Date: 05/01/18
 * Time: 14:47
 */

namespace Gvera\Helpers\commands;


use Gvera\Events\UserRegisteredEvent;
use Gvera\Helpers\entities\EntityManager;
use Gvera\Helpers\events\EventDispatcher;
use Gvera\Models\User;
use Gvera\Models\UserStatus;

class CreateNewUserCommand implements ICommand
{
    private $name;
    private $password;
    private $email;
    private $entityManager;

    public function __construct($name, $password, $email)
    {
        $this->name = $name;
        $this->password = $password;
        $this->email = $email;
        $this->entityManager = EntityManager::getInstance();
    }


    public function execute()
    {
        $status = $this->entityManager->getRepository(UserStatus::class)->find(1);

        $user = new User();
        $user->setUsername($this->name);
        $user->setPassword($this->password);
        $user->setEmail($this->email);
        $user->setCreated();
        $user->setStatus($status);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        EventDispatcher::dispatchEvent(
            UserRegisteredEvent::USER_REGISTERED_EVENT,
            new UserRegisteredEvent(
                UserRegisteredEvent::USER_REGISTERED_EVENT,
                $user->getUsername(),
                $user->getEmail()
            )
        );
    }
}