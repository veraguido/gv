<?php namespace Gvera\Helpers\validation;

class ValidationService
{
    public static function validate($field, $validationStrategies)
    {
        foreach ($validationStrategies as $strategy) {
            if(!is_a($strategy, IValidationStrategy::class)) {
                throw new Exception('Validation strategy should implement IValidationStrategy');
            }

            if ($strategy->validate($field))
                continue;

            return false;
        }

        return true;
    }
}