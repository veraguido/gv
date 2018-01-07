<?php
namespace Gvera\Cache;


interface ICache
{
    public function save($key, $value, $expirationTime = null);
    public function load($key);
    public function addToList($listKey, $list);
    public function getList($listKey);
    public function addToHashMap($hashMapKey, $key, $value);
    public function getHashMap($hashMapKey);
    public function setHashMap($hashMapKey, $array);
    public function getHashMapItem($hashMapKey, $itemKey);
    public function setExpiration($key, $expirationTime = null);
    public function exists($key);
    public function delete($key);
}