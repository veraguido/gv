<?php

namespace Gvera\Events;


class QWEEvent extends Event
{
    const QWE_NAME = 'qwe';
    private $objData = 123;

    public function __construct($name, $objData)
    {
        $this->objData = $objData;
        parent::__construct($name);
    }

    public function getObjectData()
    {
        return $this->objData;
    }


}