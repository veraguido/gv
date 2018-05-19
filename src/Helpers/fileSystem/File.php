<?php

namespace Gvera\Helpers\fileSystem;

class File
{

    private $name;
    private $size;
    private $type;
    private $temporaryName;
    private $error;

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of size
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set the value of size
     *
     * @return  self
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get the value of temporaryName
     */
    public function getTemporaryName()
    {
        return $this->temporaryName;
    }

    /**
     * Set the value of temporaryName
     *
     * @return  self
     */
    public function setTemporaryName($temporaryName)
    {
        $this->temporaryName = $temporaryName;

        return $this;
    }

    /**
     * Get the value of error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set the value of error
     *
     * @return  self
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * Get the value of type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}
