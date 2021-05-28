<?php

namespace Gvera\Validations;

use Gvera\Exceptions\EmptyValidationStrategiesException;
use Gvera\Exceptions\InvalidValidationMethodException;
use Gvera\Helpers\validation\IsNotEmptyValidationStrategy;
use Gvera\Helpers\validation\ValidationService;

class Examples extends ControllerValidationAbstract
{

    /**
     * @return bool
     * @throws EmptyValidationStrategiesException
     * @throws InvalidValidationMethodException
     */
    public function login(): bool
    {
        if (null === $this->fields || empty($this->fields)) {
            throw new \Exception('no fields to be validated');
        }

        $username = $this->fields['username'];
        $strategies = [new IsNotEmptyValidationStrategy()];
        if (!$this->validate($username, $strategies)) {
            return false;
        }

        $password = $this->fields['password'];
        if(!$this->validate($password, $strategies)) {
            return false;
        }

        return true;
    }
}