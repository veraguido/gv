<?php
namespace Gvera\Models;

/**
 * @Entity @Table(name="forgot_passwords")
 * @HasLifecycleCallbacks
 */
class ForgotPassword extends GvModel
{
    /** @Id @Column(type="integer") @GeneratedValue */
    private $id;
    /**
     * @OneToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    /** @Column(type="datetime") */
    private $created;
    /** @Column(type="boolean", options={"default":false}) */
    private $alreadyUsed;

    /**
     * Forgotpassword constructor.
     */
    public function __construct()
    {
        $this->setCreated();
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
     * @return mixed
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
