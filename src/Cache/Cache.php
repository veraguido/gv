<?php

namespace Gvera\Cache;


class Cache
{
    private static $exception;
    private static $cache;

    /**
     * it will ping redis to check the availability of the service, if it's not present it will use files as default.
     * @return FilesCache|RedisCache
     */
    public static function getCache() {

        try{
            RedisCache::getInstance()->ping();
        } catch (\Exception $e) {
            self::$exception = $e;
        }

        if (isset(self::$exception)) {
            $cache = FilesCache::getInstance();
        } else {
            $cache = RedisCache::getInstance();
        }

        return $cache;
    }
}