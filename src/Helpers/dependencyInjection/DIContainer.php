<?php
namespace Gvera\Helpers\dependencyInjection;

use Gvera\Exceptions\ClassNotFoundInDIContainerException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class DIContainer implements ContainerInterface
{
    private $map;
    private $classMap;

    private function addToMap($key, $obj)
    {
        if ($this->map === null) {
            $this->map = (object) array();
        }
        $this->map->$key = $obj;

        $this->classMap[$key] = $obj->value;
    }
    public function mapValue($key, $value)
    {
        $this->addToMap($key, (object) array(
            "value" => $value,
            "type" => "value"
        ));
    }
    public function mapClass($key, $value, $arguments = null)
    {
        $this->addToMap($key, (object) array(
            "value" => $value,
            "type" => "class",
            "arguments" => $arguments
        ));
    }
    public function mapClassAsSingleton($key, $value, $arguments = null)
    {
        $this->addToMap($key, (object) array(
            "value" => $value,
            "type" => "classSingleton",
            "instance" => null,
            "arguments" => $arguments
        ));
    }


    /**
     * @param $arguments
     * Will instanciate and return the arguments with the instance of classes injected.
     */
    private function getDIarguments($arguments)
    {
        $replacedArguments = $arguments;
        foreach ($arguments as $index => $argument) {
            if (!is_string($argument)) {
                break;
            }

            if (strpos($argument, "@") !== false) {
                $replacedArguments[$index] = $this->get(str_replace("@", "", $argument));
            }
        }

        return $replacedArguments;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     * @throws \ReflectionException
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        $className = $this->classMap[$id];
        // checking if the class exists
        if (!class_exists($className)) {
            throw new ClassNotFoundInDIContainerException("DI: missing class", array($className));
        }

        // initialized the ReflectionClass
        $reflection = new \ReflectionClass($className);
        $arguments = isset($this->map->$id->arguments) ? $this->map->$id->arguments : [];
        // creating an instance of the class
        if ($arguments === null || count($arguments) == 0) {
            $obj = new $className;
        } else {
            if (!is_array($arguments)) {
                $arguments = array($arguments);
            }

            //convert the DIArguments to actual objects
            $diArguments = $this->getDIarguments($arguments);

            $obj = $reflection->newInstanceArgs($diArguments);
        }

        // injecting
        if ($doc = $reflection->getDocComment()) {
            $lines = explode("\n", $doc);
            foreach ($lines as $line) {
                if (count($parts = explode("@Inject", $line)) > 1) {
                    $parts = explode(" ", $parts[1]);
                    if (count($parts) > 1) {
                        $key = $parts[1];
                        $key = str_replace("\n", "", $key);
                        $key = str_replace("\r", "", $key);
                        if (isset($this->map->$key)) {
                            $id = array_search($this->map->$key->value, $this->classMap);
                            switch ($this->map->$key->type) {
                                case "value":
                                    $obj->$key = $this->map->$key->value;
                                    break;
                                case "class":
                                    $obj->$key = $this->get($id);
                                    break;
                                case "classSingleton":
                                    if ($this->map->$key->instance === null) {
                                        $obj->$key = $this->map->$key->instance = $this->get($id);
                                    } else {
                                        $obj->$key = $this->map->$key->instance;
                                    }
                                    break;
                            }
                        }
                    }
                }
            }
        }
        // return the created instance
        return $obj;
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        $className = $this->classMap[$id];
        // checking if the class exists
        return class_exists($className);
    }
}
