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
     * @param $field
     * @param array $validationStrategies
     * @return bool
     * @throws \Exception
     */
    public function validate($field, array $validationStrategies)
    {
        foreach ($validationStrategies as $strategy) {
            if (!is_a($strategy, ValidationStrategyInterface::class)) {
                throw new \Exception('Validation strategy should implement ValidationStrategyInterface');
            }

            if ($strategy->isValid($field)) {
                continue;
            }

            return false;
        }
        return true;
    }
}
