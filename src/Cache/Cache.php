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
    /**
     * it will ping redis to check the availability of the service, if it's not present it will fallback
     * to files as default. As PRedis will ping true OR exception I can only catch the exception and fallback to
     * FilesCache
     * @return FilesCache|RedisClientCache
     */
    public static function getCache(): CacheInterface
    {
        try {
            return RedisClientCache::getInstance();
        } catch (\Exception $e) {
            return FilesCache::getInstance();
        }
    }
}
