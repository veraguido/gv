<?php

use PHPUnit\Framework\TestCase;
use Gvera\Events\UserRegisteredEvent;
use Gvera\Helpers\email\GvEmail;
use Gvera\Listeners\UserRegisteredEmailListener;
use Gvera\Helpers\config\Config;

class UserRegisteredEmailListenerTest extends TestCase
{
    /**
     * @test
     */
    public function testUserRegisteredEmailListener()
    {
        $event = new UserRegisteredEvent('Juan', 'juan@gv.com', false);
        $mock = $this->createMock(GvEmail::class);

        $mock->expects($this->exactly(1))
            ->method('addAddress')
            ->willReturn(null);

        $mock->expects($this->once())
            ->method('send')
            ->willReturn(true);

        $listener = new UserRegisteredEmailListener($mock);
        $listener->handleEvent($event);
    }
}
