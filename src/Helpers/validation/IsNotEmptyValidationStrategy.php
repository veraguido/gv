<?php namespace Gvera\Helpers\validation;

class IsNotEmptyValidationStrategy implements IValidationStrategy
{
    public function validate($field)
    {
        return $field == '' || $field == null;
    }
}