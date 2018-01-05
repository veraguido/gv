<?php namespace Gvera\Models;
use Gvera\Helpers\validation\EmailValidationStrategy;
use Gvera\Helpers\validation\ValidationService;

/**
 * @Entity @Table(name="users")
 * @HasLifecycleCallbacks
 */
class User extends GvModel
{
    /** @Id @Column(type="integer") @GeneratedValue */
    protected $id;

    /** @Column(type="string", length=50, unique=true, nullable=false) */
    protected $username;

    /** @Column(type="string", length=128, unique=true, nullable=false) */
    protected $email;

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        if (!$this->getService()->validateEmail($email)) {
            throw new \Exception('Email is not valid.');
        }

        $this->email = $email;
    }

    /** @Column(type="string") */
    protected $password;

    /** @Column(type="datetime") */
    protected $created;

    /** @Column(type="datetime") */
    protected $updated;

    /**
     * @OneToOne(targetEntity="UserStatus")
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
        $this->password = $this->getService()->generatePassword($password);
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