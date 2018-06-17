<?php
namespace Gvera\Controllers;

use Gvera\Cache\Cache;

/**
 * Controller Class Doc Comment
 *
 * @category Class
 * @package  Controllers
 * @author   Guido Vera <vera.a.guido@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.github.com/veraguido/gv
 */
class Index extends GvController
{
    /**
     * @httpMethod("POST")
     */
    public function index()
    {
        $this->httpResponse->asJson();
        echo json_encode(
            array(
                'gv' => array(
                    "version" => "1.0",
                    "cache" => get_class(Cache::getCache())
                )
            )
        );
    }

    public function cacheType()
    {
        $this->httpResponse->asJson();
        echo json_encode([cache => get_class(Cache::getCache())]);
    }
}
