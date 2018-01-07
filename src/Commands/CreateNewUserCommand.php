<?php

namespace Gvera\Commands;


use Gvera\Events\UserRegisteredEvent;
use Gvera\Helpers\entities\EntityManager;
use Gvera\Helpers\events\EventDispatcher;
use Gvera\Models\User;
use Gvera\Models\UserRole;
use Gvera\Models\UserStatus;

class CreateNewUserCommand implements ICommand
{
    private $name;
    private $password;
    private $email;
    private $entityManager;
    private $roleName;

    public function __construct($name, $password, $email)
    {
        $this->name = $name;
        $this->password = $password;
        $this->email = $email;
        $this->entityManager = EntityManager::getInstance();
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function execute()
    {
        $status = $this->entityManager->getRepository(UserStatus::class)->findOneBy(['status' => 'active']);
        $role = $this->entityManager->getRepository(UserRole::class)->findOneBy(['name' => 'user']);

        $user = new User();
        $user->setUsername($this->name);
        $user->setPassword($this->password);
        $user->setEmail($this->email);
        $user->setCreated();
        $user->setStatus($status);
        $user->setRole($role);

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