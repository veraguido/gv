<?php namespace Gvera\Cache;


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
final class RedisClientCache implements CacheInterface
{
    private static $instance;
    private $itemPool;

    /**
     * RedisClientCache constructor.
     * @throws \ReflectionException
     */
    private function __construct()
    {
        $this->itemPool = new CacheItemPool();
    }

    /**
     * @return RedisClientCache
     * @throws \ReflectionException
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new RedisClientCache();
        }
        
        return self::$instance;
    }

    /**
     * @param $key
     * @param $value
     * @param null $expirationTime
     * @throws \Exception
     */
    public function save($key, $value, $expirationTime = null)
    {
        $cacheItem = new CacheItem($key);
        $cacheItem->set($value);
        $cacheItem->expiresAfter($expirationTime);
        $this->itemPool->save($cacheItem);
    }

    /**
     * @param $key
     * @return mixed
     * @throws \Gvera\Exceptions\InvalidArgumentException
     */
    public function load($key)
    {
        return $this->itemPool->getItem($key)->get();
    }

    /**
     * @param $key
     * @param null $expirationTime
     * @throws \Gvera\Exceptions\InvalidArgumentException
     */
    public function setExpiration($key, $expirationTime = null)
    {
        $cacheItem = $this->itemPool->getItem($key);
        $cacheItem->expiresAfter($expirationTime);
        $this->itemPool->save($cacheItem);
    }

    /**
     * @param $key
     * @return bool
     * @throws \Gvera\Exceptions\InvalidArgumentException
     */
    public function exists($key)
    {
        return $this->itemPool->hasItem($key);
    }

    /**
     * @param $key
     * @return bool|void
     * @throws \Gvera\Exceptions\InvalidArgumentException
     */
    public function delete($key)
    {
        return $this->itemPool->deleteItem($key);
    }


    public function deleteAll()
    {
        return $this->itemPool->deleteAll();
    }
}
