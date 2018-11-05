<?php

namespace Gvera\Cache;

use Gvera\Exceptions\InvalidArgumentException;
use Gvera\Helpers\config\Config;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Predis\Client;
use Symfony\Component\Yaml\Yaml;

class CacheItemPool implements CacheItemPoolInterface
{

    private $pool = [];
    private $saveBus = [];
    private $config;
    private $poolCacheClient;

    /**
     * CacheItemPool constructor.
     * @throws \ReflectionException
     */
    public function __construct()
    {
        $this->config = Yaml::parse(file_get_contents(__DIR__ . '/../../config/config.yml'))['config']['redis'];
        $redisConfig = [
            "scheme" => "tcp",
            "host" => $this->config["host"],
            "port" => $this->config["port"],
            "cluster" => $this->config["cluster"]
        ];
        $clientCacheBufferSize = $this->config["pool_size"];

        $this->poolCacheClient = new PoolableClientCache(
            $clientCacheBufferSize,
            Client::class,
            $redisConfig
        );
    }

    /**
     * Returns a Cache Item representing the specified key.
     *
     * This method must always return a CacheItemInterface object, even in case of
     * a cache miss. It MUST NOT return null.
     *
     * @param string $key
     *   The key for which to return the corresponding Cache Item.
     *
     * @throws InvalidArgumentException
     *   If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
     *   MUST be thrown.
     *
     * @return CacheItemInterface
     *   The corresponding Cache Item.
     */
    public function getItem($key)
    {
        try {
            $client = $this->poolCacheClient->nextClient();
            $client->connect();
            $item = unserialize($client->get($key));
            $this->pool[$key] = $item;
            $client->disconnect();
        } catch (\Throwable $t) {
            throw new InvalidArgumentException('something went wrong retrieving a cache item', [$key]);
        }
        return $item;
    }

    /**
     * Returns a traversable set of cache items.
     *
     * @param array $keys
     * An indexed array of keys of items to retrieve.
     *
     * @throws InvalidArgumentException
     *   If any of the keys in $keys are not a legal value a \Psr\Cache\InvalidArgumentException
     *   MUST be thrown.
     *
     * @return array|\Traversable
     *   A traversable collection of Cache Items keyed by the cache keys of
     *   each item. A Cache item will be returned for each key, even if that
     *   key is not found. However, if no keys are specified then an empty
     *   traversable MUST be returned instead.
     */
    public function getItems(array $keys = array())
    {
        try {
            $collectedItems = [];
            foreach ($keys as $itemKey) {
                array_push($collectedItems, $this->getItem($itemKey));
            }
        } catch (\Throwable $t) {
            throw new InvalidArgumentException('something went wrong getting items from cache');
        }

        return $collectedItems;
    }

    /**
     * Confirms if the cache contains specified cache item.
     *
     * Note: This method MAY avoid retrieving the cached value for performance reasons.
     * This could result in a race condition with CacheItemInterface::get(). To avoid
     * such situation use CacheItemInterface::isHit() instead.
     *
     * @param string $key
     *    The key for which to check existence.
     *
     * @throws InvalidArgumentException
     *   If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
     *   MUST be thrown.
     *
     * @return bool
     *  True if item exists in the cache, false otherwise.
     */
    public function hasItem($key)
    {
        return array_key_exists($key, $this->pool);
    }

    /**
     * Deletes all items in the pool.
     *
     * @return bool
     *   True if the pool was successfully cleared. False if there was an error.
     */
    public function clear()
    {
        $this->pool = [];
        $this->saveBus = [];
        return $this->commit();
    }

    /**
     * Removes the item from the pool.
     *
     * @param string $key
     *   The key for which to delete
     *
     * @throws InvalidArgumentException
     *   If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
     *   MUST be thrown.
     *
     * @return bool
     *   True if the item was successfully removed. False if there was an error.
     */
    public function deleteItem($key)
    {
        if (!$this->pool[$key]) {
            throw new InvalidArgumentException('key does not exist in cache', ['key' => $key]);
        }

        $this->saveBus['delete'] = [$key] ;
        unset($this->pool[$key]);
        return $this->commit();
    }
    /**
 * Removes multiple items from the pool.
 *
 * @param array $keys
 *   An array of keys that should be removed from the pool.
 * @throws InvalidArgumentException
 *   If any of the keys in $keys are not a legal value a \Psr\Cache\InvalidArgumentException
 *   MUST be thrown.
 *
 * @return bool
 *   True if the items were successfully removed. False if there was an error.
 */
    public function deleteItems(array $keys)
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $this->pool)) {
                throw new InvalidArgumentException("there's one corrupted item passed as an argument", [$key]);
            }
            array_push($this->saveBus['delete'], $this->pool[$key]);
            unset($this->pool[$key]);
        }

        return $this->commit();
    }

    /**
     * Persists a cache item immediately.
     *
     * @param CacheItemInterface $item
     *   The cache item to save.
     *
     * @return bool
     *   True if the item was successfully persisted. False if there was an error.
     */
    public function save(CacheItemInterface $item)
    {
        $this->pool[$item->getKey()] = $item;
        $this->saveBus['save'] = [$item->getKey() => $item];
        return $this->commit();
    }

    /**
     * Sets a cache item to be persisted later.
     *
     * @param CacheItemInterface $item
     *   The cache item to save.
     *
     * @return bool
     *   False if the item could not be queued or if a commit was attempted and failed. True otherwise.
     */
    public function saveDeferred(CacheItemInterface $item)
    {
        array_push($this->pool[$item->getKey()], $item);
        $this->saveBus['save'] = [$item->getKey() => $item];
        return true;
    }

    /**
     * Persists any deferred cache items.
     *
     * @return bool
     *   True if all not-yet-saved items were successfully saved or there were none. False otherwise.
     */
    public function commit()
    {
        try {
            $client = $this->poolCacheClient->nextClient();
            $client->connect();

            if (isset($this->saveBus['save'])) {
                foreach ($this->saveBus['save'] as $keyToSave => $itemToSave) {
                    $client->set($keyToSave, serialize($itemToSave));
                    if ($itemToSave->getExpirationTime()) {
                        $client->expire($keyToSave, $itemToSave->getExpirationTime());
                    }
                }
            }

            if (isset($this->saveBus['delete'])) {
                foreach ($this->saveBus['delete'] as $keyToDelete) {
                    $client->del($keyToDelete);
                }
            }

        } catch (\Throwable $t) {
            return false;
        } finally {
            $client->disconnect();
        }
        return true;
    }

    public function deleteAll()
    {
        $this->deleteItems(array_keys($this->pool));
    }
}
