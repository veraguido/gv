<?php
/**
 * Created by PhpStorm.
 * User: guido
 * Date: 15/05/17
 * Time: 11:24
 */

namespace Gvera\Models;

/**
 * Class UserStatus
 * @package Gvera\Models
 * @Entity @Table(name="user_status")
 */
class UserStatus
{
    /** @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;

    /** @Column(type="string", nullable=false, unique=true, length=20)
     */
    protected $status;

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


}