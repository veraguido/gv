<?php

namespace Gvera\Validations;

use Gvera\Exceptions\EmptyValidationStrategiesException;
use Gvera\Exceptions\InvalidValidationMethodException;
use Gvera\Helpers\validation\ValidationService;

abstract class ControllerValidationAbstract
{
    protected string $method;
    protected ValidationService $validationService;
    protected array $fields;

    public function __construct(string $method, ValidationService $validationService, array $fields)
    {
        $this->method = $method;
        $this->validationService = $validationService;
        $this->fields = $fields;
    }

    public function validate(?string $field, array $validationStrategies): bool
    {
        if (null === $this->method) {
            throw new InvalidValidationMethodException('method in validation is not defined');
        }

        if (0 === count($validationStrategies)) {
            throw new EmptyValidationStrategiesException('there is no validation strategy to perform');
        }

        return $this->validationService->validate($field, $validationStrategies);
    }
}