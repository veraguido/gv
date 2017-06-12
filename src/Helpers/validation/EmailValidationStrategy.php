<?php namespace Gvera\Helpers\validation;

class EmailValidationStrategy implements IValidationStrategy
{

    public function validate($field)
    {
        if (!filter_var($field, FILTER_VALIDATE_EMAIL))
            return false;

        return true;
    }
}