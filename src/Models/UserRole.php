<?php
/**
 * Created by PhpStorm.
 * User: guido
 * Date: 1/6/18
 * Time: 9:48 PM
 */

namespace Gvera\Models;

/**
 * Class UserStatus
 * @package Gvera\Models
 * @Entity @Table(name="user_roles")
 */
class UserRole extends GvModel
{
    /** @Id @Column(type="integer") @GeneratedValue */
    protected $id;
    /** @Column(type="string", nullable=false, unique=true, length=20) */
    protected $name;
    /** @Column(type="integer", unique=true, nullable=false) */
    protected $rolePriority;

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


}