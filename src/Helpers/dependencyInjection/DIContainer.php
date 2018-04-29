<?php
namespace Gvera\Helpers\dependencyInjection;

class DIContainer
{
    private static $map;
    private static $classMap;

    private static function addToMap($key, $obj)
    {
        if (self::$map === null) {
            self::$map = (object) array();
        }
        self::$map->$key = $obj;

        self::$classMap[$key] = $obj->value;
    }
    public static function mapValue($key, $value)
    {
        self::addToMap($key, (object) array(
            "value" => $value,
            "type" => "value"
        ));
    }
    public static function mapClass($key, $value, $arguments = null)
    {
        self::addToMap($key, (object) array(
            "value" => $value,
            "type" => "class",
            "arguments" => $arguments
        ));
    }
    public static function mapClassAsSingleton($key, $value, $arguments = null)
    {
        self::addToMap($key, (object) array(
            "value" => $value,
            "type" => "classSingleton",
            "instance" => null,
            "arguments" => $arguments
        ));
    }

    public static function getInstanceOf($classId)
    {
        
        $className = self::$classMap[$classId];
        // checking if the class exists
        if (!class_exists($className)) {
            throw new \Exception("DI: missing class '" . $className . "'.");
        }
        
        // initialized the ReflectionClass
        $reflection = new \ReflectionClass($className);
        $arguments = isset(self::$map->$classId->arguments) ? self::$map->$classId->arguments : [];
        // creating an instance of the class
        if ($arguments === null || count($arguments) == 0) {
            $obj = new $className;
        } else {
            if (!is_array($arguments)) {
                $arguments = array($arguments);
            }

            //convert the DIArguments to actual objects
            $diArguments = self::getDIarguments($arguments);

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
                        if (isset(self::$map->$key)) {
                            $id = array_search(self::$map->$key->value, self::$classMap);
                            switch (self::$map->$key->type) {
                                case "value":
                                    $obj->$key = self::$map->$key->value;
                                    break;
                                case "class":
                                    $obj->$key = self::getInstanceOf($id, self::$map->$key->arguments);
                                    break;
                                case "classSingleton":
                                    if (self::$map->$key->instance === null) {
                                        $obj->$key = self::$map->$key->instance = self::getInstanceOf(
                                            $id,
                                            self::$map->$key->arguments
                                        );
                                    } else {
                                        $obj->$key = self::$map->$key->instance;
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
     * @param $arguments
     * Will instanciate and return the arguments with the instance of classes injected.
     */
    private static function getDIarguments($arguments)
    {
        $replacedArguments = $arguments;
        foreach ($arguments as $index => $argument) {
            if (!is_string($argument)) {
                break;
            }

            if (strpos($argument, "@") !== false) {
                $replacedArguments[$index] = self::getInstanceOf(str_replace("@", "", $argument));
            }
        }

        return $replacedArguments;
    }
}
