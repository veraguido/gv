<?php

namespace Gvera\Commands;


use Gvera\Helpers\entities\EntityManager;
use Gvera\Models\UserStatus;

class CreateUserStatusCommand implements ICommand
{

    private $statusName;


    public function __construct($statusName)
    {
        $this->statusName = $statusName;
        $this->entityManager = EntityManager::getInstance();
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function execute()
    {
        $userStatus = new UserStatus();
        $userStatus->setStatus($this->statusName);
        $this->entityManager->persist($userStatus);
        $this->entityManager->flush();
    }
}