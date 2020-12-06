<?php namespace Gvera\Models;

use Gvera\Services\UserService;

/**
 * @Entity
 * @Table(name="users")
 * @HasLifecycleCallbacks
 */
class User
{
    /** @Id @Column(type="integer") @GeneratedValue */
    protected $id;

    /** @Column(type="string", length=50, unique=true, nullable=false) */
    protected $username;

    /** @Column(type="string", length=128, unique=true, nullable=false) */
    protected $email;

    /** @Column(type="string") */
    protected $password;

    /** @Column(type="boolean") */
    protected $enabled = true;

    /** @Column(type="datetime") */
    protected $created;

    /** @Column(type="datetime") */
    protected $updated;

    /**
     * @ManyToOne(targetEntity="UserStatus")
     * @JoinColumn(name="status_id", referencedColumnName="id")
     */
    protected $status;

    /**
     * @ManyToOne(targetEntity="UserRole")
     * @JoinColumn(name="role_id", referencedColumnName="id")
     */
    protected $role;

    public function __construct()
    {
        $this->setCreated();
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return int
     */
    public function getId() :int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
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

    /**
     * @return UserStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled): void
    {
        $this->enabled = $enabled;
    }
}
