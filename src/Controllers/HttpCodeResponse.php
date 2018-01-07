<?php

namespace Gvera\Controllers;

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
class HttpCodeResponse extends GvController
{
    public function resourceNotFound()
    {
        $this->httpResponse->asJson();
        $this->viewParams = array('code' => '404', 'message' => 'resource not found.');
        $this->httpResponse->notFound();
    }
}
