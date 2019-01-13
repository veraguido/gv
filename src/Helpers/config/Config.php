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
    const CONFIG_KEY = 'gv_config';
    private $config;

    /**
     * @Cached
     * Config constructor.
     */
    public function __construct()
    {
        $this->setConfig(
            Yaml::parse(
                file_get_contents(__DIR__ . "/../../../config/config.yml")
            )["config"]
        );
    }

    public function getConfigItem($key)
    {
        return $this->config[$key];
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }
}
