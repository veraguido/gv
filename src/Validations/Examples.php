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
        if (!isset($this->fields['username']) || !isset($this->fields['password'])) {
            throw new \Exception('something went wrong with the validation');
        }

        $username = $this->fields['username'];
        $strategies = [new IsNotEmptyValidationStrategy()];
        if (!$this->validate($username, $strategies)) {
            return false;
        }

        $password = $this->fields['password'];
        if (!$this->validate($password, $strategies)) {
            return false;
        }

        return true;
    }
}
