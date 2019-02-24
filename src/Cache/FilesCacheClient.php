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
class FilesCacheClient
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function saveToFile($file, $value)
    {
        file_put_contents($this->path . $file, serialize($value));
        return true;
    }

    public function loadFromFile($file)
    {
        return unserialize(file_get_contents($this->path . $file));
    }
}
