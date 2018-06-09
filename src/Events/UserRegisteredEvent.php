<?php
namespace Gvera\Events;

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
class UserRegisteredEvent extends Event
{
    const USER_REGISTERED_EVENT  = 'user_registered_event';
    protected $username;
    protected $email;
    protected $devMode;

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return boolean
     */
    public function getDevMode()
    {
        return boolval($this->devMode);
    }

    public function __construct($username, $email, $devMode = false)
    {
        $this->username = $username;
        $this->email = $email;
        $this->devMode = $devMode;
    }
}
