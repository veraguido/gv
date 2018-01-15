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
 *
 */
class LoginCommand implements ICommand
{
    private $username;
    private $password;
    private $role;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @throws \Exception
     */
    public function execute()
    {
        $userService = new UserService();
        $userService->login($this->username, $this->password);

        if (UserService::isUserLoggedIn()) {
            EventDispatcher::dispatchEvent(
                UserLoggedInEvent::USER_LOGGED_IN_EVENT,
                new UserLoggedInEvent()
            );
        }
    }
}
