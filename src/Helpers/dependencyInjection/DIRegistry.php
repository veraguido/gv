<?php

namespace Gvera\Helpers\dependencyInjection;

use Gvera\Cache\Cache;
use Symfony\Component\Yaml\Yaml;

class DIRegistry
{
    const DI_KEY = 'gv_di';

    private $container;

    public function __construct(DIContainer $container)
    {
        $this->container = $container;
    }

    public function registerObjects()
    {
        foreach ($this->getDIObjects() as $category) {
            $classPath = $category['classPath'];

            if (!$category['objects']) {
                break;
            }

            foreach ($category['objects'] as $diKey => $diObject) {
                $singleton = isset($diObject['singleton']) ? $diObject['singleton'] : false;
                $className = $classPath . $diObject['class'];
                $arguments = isset($diObject['arguments']) ? array($diObject['arguments']) : [];
                $this->registerObject($diKey, $className, $singleton, $arguments);
            }
        }
    }

    /**
     * @return object|null
     */
    private function registerObject($objectKey, string $className, bool $singleton, array $arguments)
    {
        if ($singleton) {
            $this->container->mapClassAsSingleton(
                $objectKey,
                $className,
                $arguments
            );
            return;
        }

        $this->container->mapClass(
            $objectKey,
            $className,
            $arguments
        );
    }

    private function getDIObjects()
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
