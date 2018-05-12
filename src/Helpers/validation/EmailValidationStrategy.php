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
class EmailValidationStrategy implements ValidationStrategyInterface
{

    public function isValid($field)
    {
        return filter_var($field, FILTER_VALIDATE_EMAIL);
    }
}
