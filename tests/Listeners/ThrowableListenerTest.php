<?php

use PHPUnit\Framework\TestCase;
use Gvera\Listeners\ThrowableListener;
use Gvera\Events\ThrowableFiredEvent;
use Monolog\Logger;

class ThrowableListenerTest extends TestCase
{
    /**
     * @test
     */
    public function testListener()
    {
        $listener = new ThrowableListener();
        $listener->logger = $this->getMockedLogger();
        $event = new ThrowableFiredEvent(new Exception('asd'), false);
        $listener->handleEvent($event);
    }

    /**
     * @test
     * @expectedException Exception   
     */
    public function testListenerWithDevMode()
    {
        $listener = $this->createMock(ThrowableListener::class);
        $listener->expects($this->once())
            ->method('handleEvent')
            ->will($this->returnCallback(function($code) {
                throw new \Exception('asd');
            }));
        $event = new ThrowableFiredEvent(new Exception('asd'), true);
        $listener->handleEvent($event);
    }

    private function getMockedLogger()
    {
        $logger = $this->createMock(Logger::class);
        $logger->expects($this->once())
            ->method('err');
        return $logger;
    }
}