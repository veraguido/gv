<?php namespace Gvera\Models;

/**
 * @Entity @Table(name="users")
 */
class UserModel
{
    /** @Id @Column(type="integer") @GeneratedValue */
    protected $id;

    /** @Id @Column(type="string") */
    protected $username;

    /** @Id @Column(type="string") */
    protected $password;

    /** @Id @Column(type="datetime") */
    protected $createdAt;

    /** @Id @Column(type="datetime") */
    protected $updatedAt;

    /**
     * @Id
     * @Column(type="integer")
     * @ManyToOne(targetEntity="UserStatusModel")
     */
    protected $status;

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }
}