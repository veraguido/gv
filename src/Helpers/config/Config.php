<?php
namespace Gvera\Helpers\config;

use Gvera\Cache\Cache;
use Gvera\Cache\RedisCache;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Config
 * @package Gvera\Helpers\config
 * This class is a driver for the config, if this one is saved into redis it will get it
 * if not, it will fetch it from the yml file, and save it into the cache.
 */
class Config
{
    private static $instance;

    const CONFIG_KEY = 'gv_config';
    private $config;

    private function __construct()
    {
        if (Cache::getCache()->exists(self::CONFIG_KEY)) {
            $this->config = unserialize(Cache::getCache()->load(self::CONFIG_KEY));
        } else {
            $this->config = Yaml::parse(file_get_contents(__DIR__ . "/../../../config/config.yml"))["config"];
            Cache::getCache()->save(self::CONFIG_KEY, serialize($this->config));
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Config();
        }
        return self::$instance;
    }

    public function getConfig($key)
    {
        return $this->config[$key];
    }
}
