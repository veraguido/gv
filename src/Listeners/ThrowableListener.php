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
     * @return void
     */
    public function handleEvent($event)
    {
        $throwable = $event->getThrowable();
        if ($event->isDevMode()) {
            $httpResponse = $event->getHttpResponse();
            $httpResponse->asJson();
            $httpResponse->terminate($event->getThrowable()->getMessage());
            return;
        }

        $arguments = is_a($throwable, GvException::class) ? $throwable->getArguments() : [];
        $this->logger->err($throwable->getMessage(), $arguments);
    }
}
