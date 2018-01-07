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
class ValidationService
{
    public static function validate($field, $validationStrategies)
    {
        foreach ($validationStrategies as $strategy) {
            if (!is_a($strategy, IValidationStrategy::class)) {
                throw new Exception('Validation strategy should implement IValidationStrategy');
            }

            if ($strategy->validate($field)) {
                continue;
            }

            return false;
        }
        return true;
    }
}
