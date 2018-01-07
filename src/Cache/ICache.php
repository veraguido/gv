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
