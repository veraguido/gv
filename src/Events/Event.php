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
abstract class Event
{
    protected $name;

    /**
     *
     * Event constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}
