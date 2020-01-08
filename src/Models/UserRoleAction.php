<?php
namespace Gvera\Models;

use Doctrine\Common\Collections\ArrayCollection;

/**
* @Entity @Table(name="user_role_actions")
*/
class UserRoleAction
{

    /** @Id @Column(type="integer") @GeneratedValue */
    protected $id;

    /** @Column(type="string", nullable=false, unique=true, length=32) */
    protected $name;

    /**
     * Many Users have Many Stores.
     * @ManyToMany(targetEntity="UserRole", inversedBy="user_roles", fetch="EAGER", cascade={"persist"})
     */
    protected $userRoles;

    /**
     * UserRoleAction constructor.
     */
    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getActionName()
    {
        return $this->name;
    }

    /**
     * @param $name
     */
    public function setActionName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * @param mixed $userRoles
     */
    public function setUserRoles($userRoles): void
    {
        $this->userRoles = $userRoles;
    }

    public function addUserRole(UserRole $role)
    {
        if (null === $role) {
            return;
        }

        $role->addRoleAction($this);
        if ($this->userRoles->contains($role)) {
            return;
        }
        $this->userRoles->add($role);
    }

    public function removeUserRole(UserRole $role)
    {
        if (!$this->userRoles->contains($role)) {
            return;
        }
        $this->userRoles->removeElement($role);
    }
}
