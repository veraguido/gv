<?php

use Gvera\Helpers\validation\IsNotEmptyValidationStrategy;
use PHPUnit\Framework\TestCase;

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