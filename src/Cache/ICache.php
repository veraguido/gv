<?php
/**
 * Created by PhpStorm.
 * User: guido
 * Date: 09/08/17
 * Time: 23:29
 */

namespace Gvera\Cache;


interface ICache
{
    public function save($key, $value, $expirationTime = null);
    public function load($key);
    public function addToList($listKey, $list);
    public function getList($listKey);
    public function addToHashMap($hashMapKey, $key, $value);
    public function getHashMap($hashMapKey);
    public function getHashmapItem($hashMapKey, $itemKey);
    public function setExpiration($key, $expirationTime = null);
    public function exists($key);
}