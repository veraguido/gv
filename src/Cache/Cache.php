<?php
namespace Gvera\Cache;

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
class Cache
{
    private static $exception;

    /**
     * it will ping redis to check the availability of the service, if it's not present it will fallback
     * to files as default. As PRedis will ping true OR exception I can only catch the exception and fallback to
     * FilesCache
     * @return FilesCache|RedisCache
     */
    public static function getCache(): ICache
    {
        try {
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
