<?php

namespace Gvera\Cache;


class Cache
{
    public static function getCache() {
        return RedisCache::getInstance();
    }
}