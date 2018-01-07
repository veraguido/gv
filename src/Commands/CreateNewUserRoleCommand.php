<?php
/**
 * Created by PhpStorm.
 * User: guido
 * Date: 1/6/18
 * Time: 10:01 PM
 */

namespace Gvera\Commands;


use Gvera\Helpers\entities\EntityManager;
use Gvera\Models\UserRole;

class CreateNewUserRoleCommand implements ICommand
{
    private $roleName;
    private $priority;
    private $entityManager;

    public function __construct($name, $priority)
    {
        $this->roleName = $name;
        $this->entityManager = EntityManager::getInstance();
        $this->priority = $priority;
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function execute()
    {
        $userRole = new UserRole();
        $userRole->setName($this->roleName);
        $userRole->setRolePriority($this->priority);
        $this->entityManager->persist($userRole);
        $this->entityManager->flush();
    }
}