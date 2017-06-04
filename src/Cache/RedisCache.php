<?php namespace Gvera\Cache;

use Symfony\Component\Yaml\Yaml;

class RedisCache
{
    private static $instance;
    private $client;
    private static $config;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance)
        {
            self::$instance = new RedisCache();
            self::$config = Yaml::parse(file_get_contents("../config/config.yml"))["config"]["redis"];
        }

        return self::$instance;
    }

    public function save($key, $value)
    {
        $this->checkRedisClient();
        $this->client->connect(self::$config['host'], self::$config['port']);
        $this->client->set($key, $value);
    }

    public function load($key)
    {
        $this->checkRedisClient();
        return $this->client->get($key);
    }

    public function checkRedisClient()
    {
        if (!$this->client)
            $this->client = new \Redis();

    }
}