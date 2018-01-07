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

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function __construct($name, $username, $email)
    {
        parent::__construct($name);
        $this->username = $username;
        $this->email = $email;
    }
}
