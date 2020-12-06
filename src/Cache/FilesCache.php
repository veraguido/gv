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
final class FilesCache implements CacheInterface
{
    private static $instance;
    private static $client;
    private static $config;

    const FILES_CACHE_PATH = __DIR__ . '/../../var/cache/files/';
    const FILES_CACHE_PREFIX = 'gv_cache_files_';
    const FILES_CACHE_LIST_PREFIX = 'list_';

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new FilesCache();
            self::checkClient();
        }

        return self::$instance;
    }

    public function save($key, $value, $expirationTime = null)
    {
        self::$client->saveToFile(self::FILES_CACHE_PREFIX . $key, $value);
    }

    public function load($key)
    {
        return self::$client->loadFromFile(self::FILES_CACHE_PREFIX . $key);
    }

    /**
     * @param $key
     * @param null $expirationTime
     * @throws \Exception
     */
    public function setExpiration($key, $expirationTime = null)
    {
        throw new \Exception('FilesCache does not support expiration');
    }

    public function exists($key)
    {
        return file_exists(self::FILES_CACHE_PATH . self::FILES_CACHE_PREFIX . $key);
    }

    public function delete($key)
    {
        $path = self::FILES_CACHE_PATH . self::FILES_CACHE_PREFIX . $key;
        if (file_exists($path)) {
            unlink($path);
        }
    }

    public function deleteAll()
    {
        $files = glob(self::FILES_CACHE_PATH . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    private static function checkClient()
    {
        if (!self::$client) {
            self::$client = new FilesCacheClient(self::FILES_CACHE_PATH);
        }
    }
}
