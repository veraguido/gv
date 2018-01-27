<?php
namespace Gvera\Controllers;

use Gvera\Cache\RedisCache;

/**
 * Controller Class Doc Comment
 *
 * @category Class
 * @package  src/controllers
 * @author    Guido Vera
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.github.com/veraguido/gv
 *
 */
class Index extends GvController
{
    public function index()
    {
        $this->httpResponse->asJson();
        echo json_encode(
            array(
                'gv' => array(
                    "version" => "1.0",
                    "redis" => (bool) RedisCache::getInstance()->ping()
                )
            )
        );
    }
}
