<?php

namespace Gvera\Commands;

use Gvera\Exceptions\NotFoundException;
use Gvera\Helpers\entities\GvEntityManager;
use Gvera\Models\UserRole;
use Gvera\Models\UserRoleAction;

class CreateOrAssignUserRoleActionCommand implements CommandInterface
{
    /**
     * @var string
     */
    private $actionName;
    /**
     * @var string
     */
    private $userRoleName;
    /**
     * @var GvEntityManager
     */
    private $entityManager;


    /**
     * CreateOrAssignUserRoleActionCommand constructor.
     * @param string $actionName
     * @param string $userRoleName
     * @param GvEntityManager $entityManager
     */
    public function __construct(string $actionName, string $userRoleName, GvEntityManager $entityManager)
    {
        $this->actionName = $actionName;
        $this->userRoleName = $userRoleName;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws NotFoundException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function execute():void
    {
        $exists = $this->entityManager->getRepository(UserRoleAction::class)
            ->findOneBy(['name' => $this->actionName]);
        $action = $exists ?? new UserRoleAction();
        $action->setActionName($this->actionName);

        $userRole = $this->entityManager->getRepository(UserRole::class)
            ->findOneBy(['name' => $this->userRoleName]);

        if (null === $userRole) {
            throw new NotFoundException('user role was not found', ['userRoleName' => $this->userRoleName]);
        }

        $action->addUserRole($userRole);
        $this->entityManager->persist($action);
        $this->entityManager->flush();
    }
}
