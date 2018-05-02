<?php
namespace Gvera\Listeners;

use Gvera\Events\Event;
use Gvera\Helpers\config\Config;
use Gvera\Helpers\email\GvEmail;

/**
 * Listener Class Doc Comment
 *
 * @category Class
 * @package  src/listeners
 * @author    Guido Vera
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.github.com/veraguido/gv
 * @Inject config
 *
 */
class UserRegisteredEmailListener implements EventListener
{

    public function handleEvent(Event $event)
    {

        if (boolval($this->config->getConfig('devmode')) === false) {
            $username = $event->getUserName();
            $message = "Hi $username we want to let you know that your account is registered :)";
            $newUserEmail = new GvEmail(
                false,
                "gv account created",
                $message,
                $message
            );

            $newUserEmail->addAddress($event->getEmail());
            $newUserEmail->send();
        }
    }
}
