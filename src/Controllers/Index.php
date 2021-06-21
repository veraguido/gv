<?php
namespace Gvera\Controllers;

use Gvera\Cache\Cache;
use Gvera\Helpers\http\JSONResponse;

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
    public function index()
    {
        $welcomeJson = [
            'gv' => ["Hello there :)"]
            ];
        $this->httpResponse->response(new JSONResponse($welcomeJson));
    }

    public function cacheType()
    {
        $this->httpResponse->response(new JSONResponse(["cache" => get_class(Cache::getCache())]));
    }

    public function opcache()
    {
        $this->httpResponse->response(new JSONResponse(["opcache_enabled" => opcache_get_status()['opcache_enabled']]));
    }
}
