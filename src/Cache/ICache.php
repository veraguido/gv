<?php
namespace Gvera\Cache;

/**
 * Cache Interface Doc Comment
 *
 * @category Interface
 * @package  src/cache
 * @author    Guido Vera
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.github.com/veraguido/gv
 *
 */
interface ICache
{
    /**
     * @return void
     */
    public function save($key, $value, $expirationTime = null);
    /**
     * @return mixed
     */
    public function load($key);
    /**
     * @return void
     */
    public function addToList($listKey, $list);
    /**
     * @return array
     */
    public function getList($listKey);
    /**
     * @return void
     */
    public function addToHashMap($hashMapKey, $key, $value);
    /**
     * @return array
     */
    public function getHashMap($hashMapKey);
    /**
     * @return void
     */
    public function setHashMap($hashMapKey, $array);
    /**
     * @return mixed
     */
    public function getHashMapItem($hashMapKey, $itemKey);
    /**
     * @return void
     */
    public function setExpiration($key, $expirationTime = null);
    /**
     * @return bool
     */
    public function exists($key);
    /**
     * @return void
     */
    public function delete($key);
}
