<?php

use PHPUnit\Framework\TestCase;
use Gvera\Listeners\ThrowableListener;
use Gvera\Events\ThrowableFiredEvent;
use Monolog\Logger;
use Gvera\Helpers\http\HttpResponse;

class ThrowableListenerTest extends TestCase
{
    /**
     * @test
     */
    public function testListener()
    {
        $listener = new ThrowableListener();
        $listener->logger = $this->getMockedLogger();
        $event = new ThrowableFiredEvent(new Exception('asd'), false, $this->createMock(HttpResponse::class));
        $listener->handleEvent($event);
    }

    /**
     * @test
     */
    public function testListenerWithDevMode()
    {
        $listener = new ThrowableListener();
        $event = new ThrowableFiredEvent(new Exception('asd'), true, $this->getMockedHttpResponse());
        $listener->handleEvent($event);
    }
    

    private function getMockedLogger()
    {
        $logger = $this->createMock(Logger::class);
        $logger->expects($this->once())
            ->method('err');
        return $logger;
    }

    private function getMockedHttpResponse()
    {
        $httpResponse = $this->createMock(HttpResponse::class);

        $httpResponse->expects($this->exactly(1))
            ->method('terminate');

        return $httpResponse;
    }
}