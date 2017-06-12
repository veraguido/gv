<?php namespace Gvera\Helpers\validation;

class NameValidationStrategy implements IValidationStrategy
{

    public function validate($field)
    {
        if (!preg_match("/^[a-zA-Z]*$/", $field))
            return false;

        return true;
    }
}