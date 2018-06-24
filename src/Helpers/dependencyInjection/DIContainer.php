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
     * @return object
     * Will instanciate and return the arguments with the instance of classes injected.
     */
    private function getDIarguments($arguments)
    {
        $replacedArguments = $arguments;
        foreach ($arguments as $index => $argument) {
            if (is_array($argument)) {
                return $this->getDIarguments($argument);
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
     * @return object
     */
    public function get($id)
    {
        $className = $this->classMap[$id];
        // checking if the class exists
        $this->checkClassExist($className);

        // initialized the ReflectionClass
        $reflection = new \ReflectionClass($className);
        $arguments = isset($this->map->$id->arguments) ? $this->map->$id->arguments : [];
        // creating an instance of the class
        $obj = $this->createInstanceOfNewClass($reflection, $className, $arguments);

        // injecting
        if ($doc = $reflection->getDocComment()) {
            $lines = explode("\n", $doc);
            $this->checkInjectionLinesInComments($lines, $obj);
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

    /**
     * @return void
     */
    private function checkInjectionLinesInComments($lines, $object)
    {
        foreach ($lines as $line) {
            if (count($parts = explode("@Inject", $line)) > 1) {
                $this->injectDependency($parts, $object);
            }
        }
    }

    /**
     * @return void
     */
    private function injectDependency($parts, $object)
    {
        $parts = explode(" ", $parts[1]);
        if (count($parts) > 1) {
            $this->generateObjectDependencies($object, $parts);
        }
    }

    /**
     * @throws ClassNotFoundInDIContainerException
     * @return void
     */
    private function checkClassExist($className)
    {
        if (!class_exists($className)) {
            throw new ClassNotFoundInDIContainerException("DI: missing class $className", array($className));
        }
    }

    /**
     * @return void
     */
    private function generateObjectDependencies($object, $dependencies)
    {
        $key = $dependencies[1];
        $key = str_replace("\n", "", $key);
        $key = str_replace("\r", "", $key);

        if (!isset($this->map->$key)) {
            return;
        }

        $id = array_search($this->map->$key->value, $this->classMap);
        $this->generateResource($id, $key, $object, $this->map->$key->type);
    }

    /**
     * @return void
     */
    private function generateResource($id, $key, $object, $type)
    {
        if ($type === "value") {
            $object->$key = $this->map->$key->value;
            return;
        }

        $this->generateClass($id, $key, $object, $type);
    }

    /**
     * @return void
     */
    private function generateClass($id, $key, $object, $type)
    {
        if ($type === "class") {
            $object->$key = $this->get($id);
            return;
        }

        if ($type === "classSingleton") {
            $this->generateSingletonDependency($key, $object, $id);
            return;
        }
    }

    /**
     * @return void
     */
    private function generateSingletonDependency($key, $object, $id)
    {
        if ($this->map->$key->instance === null) {
            $object->$key = $this->map->$key->instance = $this->get($id);
        } else {
            $object->$key = $this->map->$key->instance;
        }
    }

    /**
     * @return object
     */
    private function createInstanceOfNewClass($reflectionClass, $className, $arguments)
    {
        if ($arguments === null || count($arguments) == 0) {
            return new $className;
        }

        if (!is_array($arguments)) {
            $arguments = array($arguments);
        }

        //convert the DIArguments to actual objects
        $diArguments = $this->getDIarguments($arguments);
        return $reflectionClass->newInstanceArgs($diArguments);
    }
}
