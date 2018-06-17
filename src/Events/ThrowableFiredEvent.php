<?php

namespace Gvera\Events;

use Gvera\Helpers\http\HttpResponse;

/**
 * Event Class Doc Comment
 *
 * @category Class
 * @package  src/events
 * @author    Guido Vera
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.github.com/veraguido/gv
 *
 */
class ThrowableFiredEvent extends Event
{
    const THROWABLE_FIRED_EVENT = 'throwable_fired_event';
    protected $throwable;
    protected $devMode;
    protected $httpResponse;

    public function __construct(\Throwable $throwable, $devMode, HttpResponse $httpResponse)
    {
        $this->throwable = $throwable;
        $this->devMode = $devMode;
        $this->httpResponse = $httpResponse;
    }

    /**
     * @return \Throwable
     */
    public function getThrowable(): \Throwable
    {
        return $this->throwable;
    }

    /**
     * @return bool
     */
    public function isDevMode(): bool
    {
        return $this->devMode;
    }

    /**
     * Get the value of httpResponse
     */
    public function getHttpResponse()
    {
        return $this->httpResponse;
    }
}
