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
    /**
     * This function will run validation strategies on a specific field, an array of validation strategies
     * are needed for the second parameter.
     * @param $field
     * @param $validationStrategies
     * @return bool
     */
    public static function validate($field, $validationStrategies)
    {
        foreach ($validationStrategies as $strategy) {
            if (!is_a($strategy, ValidationStrategyInterface::class)) {
                throw new Exception('Validation strategy should implement ValidationStrategyInterface');
            }

            if ($strategy->validate($field)) {
                continue;
            }

            return false;
        }
        return true;
    }
}
