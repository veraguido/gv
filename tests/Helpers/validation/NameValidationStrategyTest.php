<?php

use Gvera\Helpers\validation\NameValidationStrategy;
use PHPUnit\Framework\TestCase;

class NameValidationStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function testValidation()
    {
        $validation = new NameValidationStrategy();
        $this->assertTrue($validation->isValid('Pepe'));
        $this->assertFalse($validation->isValid('p3p3'));
        $this->assertFalse($validation->isValid('Juan @rsenico'));
        $this->assertFalse($validation->isValid('Emil1o Pepini'));
        $this->assertFalse($validation->isValid('elGuaCh!n'));

    }
}