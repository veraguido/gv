<?php
namespace Gvera\Models;

/**
 * @Entity @Table(name="forgot_passwords")
 * @HasLifecycleCallbacks
 */
class ForgotPassword
{
    /** @Id @Column(type="integer") @GeneratedValue */
    private $id;
    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    /** @Column(type="datetime") */
    private $created;
    /** @Column(type="boolean", options={"default":false}) */
    private $alreadyUsed;
    /** @Column(type="string", length=50, unique=true, nullable=false) */
    private $forgotPasswordKey;

    /**
     * @return mixed
     */
    public function getForgotPasswordKey()
    {
        return $this->forgotPasswordKey;
    }

    /**
     * ForgotPassword constructor.
     * @param $user
     * @param $key
     */
    public function __construct($user, $key)
    {
        $this->setUser($user);
        $this->setCreated();
        $this->forgotPasswordKey = $key;
        $this->alreadyUsed = false;
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

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
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
    }

    /**
     * @return mixed
     */
    public function getAlreadyUsed()
    {
        return $this->alreadyUsed;
    }

    /**
     * @param mixed $alreadyUsed
     */
    public function setAlreadyUsed($alreadyUsed)
    {
        $this->alreadyUsed = $alreadyUsed;
    }
}
