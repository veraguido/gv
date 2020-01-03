<?php
namespace Gvera\Commands;

use Gvera\Events\UserLoggedInEvent;
use Gvera\Helpers\events\EventDispatcher;
use Gvera\Services\UserService;

/**
 * Command Class Doc Comment
 *
 * @category Class
 * @package  src/commands
 * @author    Guido Vera
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.github.com/veraguido/gv
 */
class LoginCommand implements CommandInterface
{
    private $username;
    private $password;
    private $userService;
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    public function __construct(UserService $userService, EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->userService = $userService;
    }

    /**
     * @throws \Exception
     */
    public function execute()
    {
        $this->userService->login($this->username, $this->password);

        if ($this->userService->isUserLoggedIn()) {
            $this->eventDispatcher::dispatchEvent(
                UserLoggedInEvent::USER_LOGGED_IN_EVENT,
                new UserLoggedInEvent()
            );
        }
    }

    /**
     * Get the value of username
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of password
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
}
