<?php

namespace Gvera\Helpers\dependencyInjection;

use Gvera\Cache\Cache;
use Symfony\Component\Yaml\Yaml;

class DIRegistry
{
    const DI_KEY = 'gv_di';

    public static function registerObjects()
    {
        foreach (self::getDIObjects() as $category) {
            $classPath = $category['classPath'];

            if (!$category['objects']) {
                break;
            }

            foreach ($category['objects'] as $diKey => $diObject) {
                $singleton = isset($diObject['singleton']) ? $diObject['singleton'] : false;
                $className = $classPath . $diObject['class'];
                $arguments = isset($diObject['arguments']) ? $diObject['arguments'] : [];
                if ($singleton) {
                    DIContainer::mapClassAsSingleton(
                        $diKey,
                        $className,
                        $arguments
                    );
                    break;
                }

                DIContainer::mapClass(
                    $diKey,
                    $className,
                    $arguments
                );
            }
        }
    }

    private static function getDIObjects()
    {
        if (Cache::getCache()->exists(self::DI_KEY)) {
            $diObjects = unserialize(Cache::getCache()->load(self::DI_KEY));
        } else {
            $diObjects = Yaml::parse(file_get_contents(__DIR__ . "/../../../config/ioc.yml"));
            Cache::getCache()->save(self::DI_KEY, serialize($diObjects));
        }

        return $diObjects;
    }
}
