<?php

namespace Gvera\Models;


abstract class GvModel
{
    private $service;
    private $serviceName;
    const SERVICES_PREFIX = "Gvera\\Services\\";

    protected function getService() {
        if (!$this->service) {
            $this->serviceName = self::SERVICES_PREFIX . (new \ReflectionClass($this))->getShortName();
            if (!class_exists($this->serviceName)) {
                throw new \Exception("service {$this->serviceName} doesn't exist. Please verify the name.");
            }

            $this->service = new $this->serviceName();
        }
        return $this->service;
    }

}