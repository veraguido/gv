<?php

namespace Gvera\Helpers\http;

use Gvera\Exceptions\NotFoundException;
use Gvera\Helpers\validation\ValidationService;
use Gvera\Validations\ControllerValidationAbstract;

class HttpRequestValidator
{

    private const VALIDATIONS_PREFIX = 'Gvera\\Validations\\';
    private ValidationService $validationService;

    public function __construct(ValidationService $validationService)
    {
        $this->validationService = $validationService;
    }

    /**
     * @param array $fields
     * @param string $validationClassName
     * @param string $validationMethod
     * @return mixed
     * @throws NotFoundException
     */
    public function validate(array $fields, string $validationClassName, string $validationMethod)
    {
        $validationFullClassName = self::VALIDATIONS_PREFIX . $validationClassName;

        if (!class_exists($validationFullClassName)) {
            throw new NotFoundException('validation class does not exist');
        }

        $validation = new $validationFullClassName($validationMethod, $this->validationService, $fields);

        if (!method_exists($validation, $validationMethod)) {
            throw new NotFoundException('validation method ' . $validationMethod . ' does not exist');
        }

        return $validation->$validationMethod();
    }
}