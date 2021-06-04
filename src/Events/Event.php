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
    protected string $id = '';
    protected bool $stopPropagation = false;

    public function __construct($id = '')
    {
        $this->id = $id;
    }

    /**
     * @return boolean
     */
    public function hasStopPropagation(): bool
    {
        return $this->stopPropagation;
    }

    /**
     * @param boolean $stopPropagation
     */
    public function setStopPropagation(bool $stopPropagation)
    {
        $this->stopPropagation = $stopPropagation;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
