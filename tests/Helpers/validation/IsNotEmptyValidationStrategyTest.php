<?php

use PHPUnit\Framework\TestCase;
use Gvera\Helpers\validation\EmailValidationStrategy;
use Gvera\Helpers\validation\IsNotEmptyValidationStrategy;

class IsNotEmptyValidationStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function testValidation()
    {
        $validation = new IsNotEmptyValidationStrategy();
        $this->assertTrue($validation->isValid('asd@asd.com'));
        $this->assertFalse($validation->isValid(''));
        $this->assertFalse($validation->isValid(null));
        $this->assertFalse($validation->isValid([]));

    }
}