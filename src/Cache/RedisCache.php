<?php namespace Gvera\Cache;

use Predis\Client;
use Symfony\Component\Yaml\Yaml;

class RedisCache
{
    private static $instance;
    private static $client;
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
            self::checkRedisClient();
        }

        return self::$instance;
    }

    public function save($key, $value, $expirationTime = null)
    {
        self::$client->set($key, $value);
        if ($expirationTime)
            self::$client->expire($key, $expirationTime);
    }

    public function load($key)
    {
        return self::$client->get($key);
    }

    public function addToList($listKey, $list)
    {
        self::$client->lpush($listKey, $list);
    }

    public function getList($listKey)
    {
        $list = self::$client->lrange($listKey, 0, -1);
        return $list;
    }

    private static function checkRedisClient()
    {
        if (!self::$client)

            self::$client  = new Client(array(
                "scheme" => "tcp",
                "host" => self::$config["host"],
                "port" => self::$config["port"],
                ));
    }
}