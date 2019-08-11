<?php
namespace Gvera\Listeners;

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
     * @return void
     */
    public function handleEvent($event);
}
