<?php
/**
 * Created by PhpStorm.
 * User: guido
 * Date: 04/11/18
 * Time: 21:16
 */

namespace Gvera\Cache;

use Psr\Cache\CacheItemInterface;

class CacheItem implements CacheItemInterface
{
    private $key;
    private $value;
    private $expiration;
    private $timeToLive = null;

    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Returns the key for the current cache item.
     *
     * The key is loaded by the Implementing Library, but should be available to
     * the higher level callers when needed.
     *
     * @return string
     *   The key string for this cache item.
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Retrieves the value of the item from the cache associated with this object's key.
     *
     * The value returned must be identical to the value originally stored by set().
     *
     * If isHit() returns false, this method MUST return null. Note that null
     * is a legitimate cached value, so the isHit() method SHOULD be used to
     * differentiate between "null value was found" and "no value was found."
     *
     * @return mixed
     *   The value corresponding to this cache item's key, or null if not found.
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * Confirms if the cache item lookup resulted in a cache hit.
     *
     * Note: This method MUST NOT have a race condition between calling isHit()
     * and calling get().
     *
     * @return bool
     *   True if the request resulted in a cache hit. False otherwise.
     */
    public function isHit()
    {
        return isset($this->value);
    }

    /**
     * Sets the value represented by this cache item.
     *
     * The $value argument may be any item that can be serialized by PHP,
     * although the method of serialization is left up to the Implementing
     * Library.
     *
     * @param mixed $value
     *   The serializable value to be stored.
     *
     * @return static
     *   The invoked object.
     */
    public function set($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param \DateTimeInterface $expiration
     * @return CacheItemInterface|void
     * @throws \Exception
     */
    public function expiresAt($expiration)
    {
        if ($this->timeToLive) {
            throw new \Exception("timeToleave is already set and expiresAt shouldn't be called");
        }
        $this->expiration = $expiration;
    }

    /**
     * @param \DateInterval|int $time
     * @return CacheItemInterface|void
     * @throws \Exception
     */
    public function expiresAfter($time)
    {
        if ($this->expiration) {
            throw new \Exception("expiration is already set and expiresAfter shouldn't be called");
        }
        $this->timeToLive = $time;
    }

    public function getExpirationTime()
    {
        return $this->timeToLive;
    }
}
