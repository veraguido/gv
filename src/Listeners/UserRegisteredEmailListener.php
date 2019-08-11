<?php
namespace Gvera\Listeners;

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
class UserRegisteredEmailListener implements EventListenerInterface
{
    private const DEFAULT_SUBJECT = 'gv account created';
    private $email;

    public function __construct(GvEmail $email)
    {
        $this->email = $email;
    }

    public function handleEvent($event)
    {
        if ($event->getDevMode() === false) {
            $username = $event->getUserName();
            $message = "Hi $username we want to let you know that your account is registered :)";
            $this->email->setSubject(self::DEFAULT_SUBJECT);
            $this->email->setBody($message);
            $this->email->setAlternativeBody($message);
            $this->email->addAddress($event->getEmail());
            $this->email->send();
        }
    }
}
