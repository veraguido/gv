<?php

namespace Gvera\Listeners;

use Gvera\Exceptions\GvException;
use Monolog\Logger;

/**
 * @Inject logger
 */
class ThrowableListener implements EventListenerInterface
{
    public $logger;

    /**
     * @param $event
     * @return void
     */
    public function handleEvent($event)
    {
        $throwable = $event->getThrowable();
        $httpResponse = $event->getHttpResponse();
        $arguments = is_a($throwable, GvException::class) ? $throwable->getArguments() : [];
        if ($event->isDevMode()) {
            echo $event->getThrowable()->getMessage() . PHP_EOL;
            var_dump($arguments);
        }

        $this->logger->err($throwable->getMessage(), $arguments);
        $httpResponse->response($event->getThrowable()->getMessage());
    }
}
