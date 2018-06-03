<?php

use PHPUnit\Framework\TestCase;
use Gvera\Helpers\validation\ValidationService;
use Gvera\Helpers\validation\EmailValidationStrategy;
use Gvera\Helpers\validation\NameValidationStrategy;
use Gvera\Helpers\validation\IsNotEmptyValidationStrategy;
use Gvera\Helpers\http\HttpResponse;

class ValidationServiceTest extends TestCase
{
    /**
     * @test
     */
    public function testService()
    {
        $service = new ValidationService();
        $emailValidation = new EmailValidationStrategy();
        $notEmptyValidation = new IsNotEmptyValidationStrategy();
        $this->assertTrue(
            $service->validate(
                'peperino@pomoro.com',
                 [$emailValidation, $notEmptyValidation]
                 )
            );
    }

    /**
     * @test
     * @expectedException Exception
     */
    public function testException()
    {
        $service = new ValidationService();
        $service->validate('a', [new HttpResponse()]);
    }
}