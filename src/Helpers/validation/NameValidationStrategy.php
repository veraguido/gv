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
class NameValidationStrategy implements ValidationStrategyInterface
{
    public function validate($field)
    {
        if (!preg_match("/^[a-zA-Z]*$/", $field)) {
            return false;
        }

        return true;
    }
}
