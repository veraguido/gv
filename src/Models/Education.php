<?php

namespace Gvera\Models;

/**
* @Entity @Table(name="education")
*/
class Education extends GvModel
{
    /** @Id @Column(type="integer") @GeneratedValue */
    protected $id;
    /** @Column(type="string") */
    protected $instituteName;
    /** @Column(type="string") */
    protected $careerName;
    /** @Column(type="string") */
    protected $description;
    /** @Column(type="string") */
    protected $months;

    /**
     * @return mixed
     */
    public function getInstituteName()
    {
        return $this->instituteName;
    }

    /**
     * @param mixed $instituteName
     */
    public function setInstituteName($instituteName)
    {
        $this->instituteName = $instituteName;
    }

    /**
     * @return mixed
     */
    public function getCareerName()
    {
        return $this->careerName;
    }

    /**
     * @param mixed $careerName
     */
    public function setCareerName($careerName)
    {
        $this->careerName = $careerName;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getMonths()
    {
        return $this->months;
    }

    /**
     * @param mixed $months
     */
    public function setMonths($months)
    {
        $this->months = $months;
    }

}