<?php
namespace Gvera\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Gvera\Helpers\entities\GvEntityManager;

/**
 * Class UserStatus
 * @package Gvera\Models
 * @Entity @Table(name="user_roles")
 */
class UserRole
{
    /** @Id @Column(type="integer") @GeneratedValue */
    protected $id;
    /** @Column(type="string", nullable=false, unique=true, length=20) */
    protected $name;
    /** @Column(type="integer", unique=true, nullable=false) */
    protected $rolePriority;

    /**
     * Many Users have Many Stores.
     * @ManyToMany(targetEntity="UserRoleAction", inversedBy="user_role_actions", fetch="EAGER", cascade={"persist"})
     */
    protected $userRoleActions;

    /**
     * UserRole constructor.
     * @param GvEntityManager $entityManager
     */
    public function __construct(GvEntityManager $entityManager)
    {
        $this->userRoleActions = new PersistentCollection($entityManager, self::class, new ArrayCollection());
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getRolePriority()
    {
        return $this->rolePriority;
    }

    /**
     * @param mixed $rolePriority
     */
    public function setRolePriority($rolePriority)
    {
        $this->rolePriority = $rolePriority;
    }

    /**
     * @return PersistentCollection
     */
    public function getUserRoleActions(): PersistentCollection
    {
        return $this->userRoleActions;
    }

    /**
     * @param PersistentCollection $userRoleActions
     */
    public function setUserRoleActions(PersistentCollection $userRoleActions): void
    {
        $this->userRoleActions = $userRoleActions;
    }

    public function addRoleAction(UserRoleAction $roleAction)
    {
        if (null === $roleAction) {
            return;
        }

        if ($this->userRoleActions->contains($roleAction)) {
            return;
        }
        $this->userRoleActions->add($roleAction);
    }

    public function removeRoleAction(UserRoleAction $roleAction)
    {
        if (!$this->userRoleActions->contains($roleAction)) {
            return;
        }
        $this->userRoleActions->removeElement($roleAction);
    }
}
