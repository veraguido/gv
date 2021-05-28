<?php namespace Gvera\Helpers\validation;

/**
 * Validation Class Doc Comment
 *
 * @category Class
 * @package  src/helpser/validation
 * @author    Guido Vera
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.github.com/veraguido/gv
 *
 */
class IsNotEmptyValidationStrategy implements ValidationStrategyInterface
{
    public function isValid(?string $field)
    {
        return $field != '' && $field != null;
    }
}
