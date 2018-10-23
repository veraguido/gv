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
     * @httpMethod("GET")
     */
    public function index()
    {
        $this->httpResponse->asJson();
        $welcomeJson = [
            'gv' => [
                "version" => "1.4",
                "cache" => get_class(Cache::getCache())
                 ]
            ];
        $this->httpResponse->response($welcomeJson);
    }

    public function cacheType()
    {
        $this->httpResponse->asJson();
        $this->httpResponse->response(["cache" => get_class(Cache::getCache())]);
    }
}
