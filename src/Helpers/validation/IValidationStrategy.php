<?php namespace Gvera\Helpers\validation;

interface IValidationStrategy
{
    public function validate($field);
}