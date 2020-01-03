<?php
namespace Gvera\Listeners;

use Gvera\Events\Event;

/**
 * Listener Class Doc Comment
 *
 * @category Interface
 * @package  src/listeners
 * @author    Guido Vera
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.github.com/veraguido/gv
 *
 */
interface EventListenerInterface
{
    /**
     * @param Event $event
     * @return mixed
     */
    public function handleEvent(Event $event);
}
