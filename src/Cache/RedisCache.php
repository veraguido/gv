<?php namespace Gvera\Cache;

class RedisCache
{
    private static $instance;
    private $client;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance)
        {
            self::$instance = new RedisCache();
        }

        return self::$instance;
    }

    public function save($key, $value)
    {
        $this->checkRedisClient();

        $this->client->connect("127.0.0.1", 6379);
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