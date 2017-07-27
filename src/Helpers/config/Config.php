<?php

namespace Gvera\Helpers\config;

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
        if (RedisCache::getInstance()->exists(self::CONFIG_KEY)) {
            $this->config = unserialize(RedisCache::getInstance()->load(self::CONFIG_KEY));
        } else {
            $this->config = Yaml::parse(file_get_contents("../config/config.yml"))["config"];
            RedisCache::getInstance()->save(self::CONFIG_KEY, serialize($this->config));
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