<?php namespace Gvera\Models;

/**
 * @Entity @Table(name="users")
 * @HasLifecycleCallbacks
 */
class User
{
    /** @Id @Column(type="integer") @GeneratedValue */
    protected $id;

    /** @Column(type="string") */
    protected $username;

    /** @Column(type="string") */
    protected $password;

    /** @Column(type="datetime") */
    protected $created;

    /** @Column(type="datetime") */
    protected $updated;

    /**
     * @OneToOne(targetEntity="UserStatusModel")
     * @JoinColumn(name="status_id", referencedColumnName="id")
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

    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param $created
     * @PrePersist
     */
    public function setCreated()
    {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param $updated
     * @PreUpdate
     */
    public function setUpdated()
    {
        $this->updated = new \DateTime();
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