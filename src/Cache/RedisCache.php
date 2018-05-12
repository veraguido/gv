<?php namespace Gvera\Cache;

use Predis\Client;
use Symfony\Component\Yaml\Yaml;

/**
 * Cache Class Doc Comment
 *
 * @category Class
 * @package  src/cache
 * @author    Guido Vera
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.github.com/veraguido/gv
 *
 */
final class RedisCache implements CacheInterface
{
    private static $instance;
    private static $client;
    private static $config;

    private function __construct()
    {
    }

    /**
     * @return RedisCache
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new RedisCache();
            self::$config = Yaml::parse(file_get_contents(__DIR__ . '/../../config/config.yml'))['config']['redis'];
            self::checkRedisClient();
        }

        return self::$instance;
    }

    public function save($key, $value, $expirationTime = null)
    {
        self::$client->set($key, $value);
        if ($expirationTime) {
            self::$client->expire($key, $expirationTime);
        }
    }

    public function load($key)
    {
        return self::$client->get($key);
    }

    public function addToList($listKey, $list)
    {
        self::$client->lpush($listKey, $list);
    }

    /**
     * @return array
     */
    public function getList($listKey)
    {
        $list = self::$client->lrange($listKey, 0, -1);
        return $list;
    }

    public function addToHashMap($hashMapKey, $key, $value)
    {
        self::$client->hset($hashMapKey, $key, $value);
    }

    /**
     * @return array
     */
    public function getHashMap($hashMapKey)
    {
        return self::$client->hgetall($hashMapKey);
    }

    public function setHashMap($hashMapKey, $array)
    {
        foreach ($array as $key => $value) {
            $this->addToHashMap($hashMapKey, $key, $value);
        }
    }

    /**
     * @return mixed
     */
    public function getHashMapItem($hashMapKey, $itemKey)
    {
        return self::$client->hget($hashMapKey, $itemKey);
    }

    public function setExpiration($key, $expirationTime = null)
    {
        self::$client->expire($key, $expirationTime);
    }

    /**
     * @return bool
     */
    public function exists($key)
    {
        return self::$client->exists($key);
    }

    /**
     * @return void
     */
    public function delete($key)
    {
        return self::$client->del($key);
    }

    /**
     * @throws Exception
     * @return bool
     */
    public function ping()
    {
        return self::$client->ping();
    }

    public function flushAll()
    {
        self::$client->flushall();
    }

    private static function checkRedisClient()
    {
        if (!self::$client) {
            self::$client  = new Client(array(
                "scheme" => "tcp",
                "host" => self::$config["host"],
                "port" => self::$config["port"],
            ));
        }
    }
}
