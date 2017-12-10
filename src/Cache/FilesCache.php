<?php
/**
 * Created by PhpStorm.
 * User: guido
 * Date: 10/12/17
 * Time: 09:45
 */

namespace Gvera\Cache;


class FilesCache implements ICache
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
        if (!self::$instance)
        {
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

    public function addToList($listKey, $list)
    {
        $this->save(self::FILES_CACHE_LIST_PREFIX . $listKey, serialize($list));
    }

    public function getList($listKey)
    {
        return unserialize($this->load(self::FILES_CACHE_LIST_PREFIX . $listKey));
    }

    public function addToHashMap($hashMapKey, $key, $value)
    {
        // TODO: Implement addToHashMap() method.
    }

    public function getHashMap($hashMapKey)
    {
        // TODO: Implement getHashMap() method.
    }

    public function setHashMap($hashMapKey, $array)
    {
        // TODO: Implement setHashMap() method.
    }

    public function getHashMapItem($hashMapKey, $itemKey)
    {
        // TODO: Implement getHashMapItem() method.
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
        return file_exists(self::FILES_CACHE_PATH . $key);
    }

    public function delete($key)
    {
        unlink(self::FILES_CACHE_PREFIX . $key);
    }

    private static function checkClient()
    {
        if(!self::$client)
            self::$client = new FilesCacheClient(self::FILES_CACHE_PATH);
    }
}