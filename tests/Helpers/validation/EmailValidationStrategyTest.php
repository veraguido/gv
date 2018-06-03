<?php

use PHPUnit\Framework\TestCase;
use Gvera\Helpers\validation\EmailValidationStrategy;

class EmailValidationStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function testValidation()
    {
        $validation = new EmailValidationStrategy();
        $this->assertTrue($validation->isValid('asd@asd.com'));
        $this->assertFalse($validation->isValid('asdasd.com'));
        $this->assertFalse($validation->isValid('asdasdcom'));
        $this->assertFalse($validation->isValid('asdasd@asd@asd.com'));

    }
}
