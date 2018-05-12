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
            die($throwable->getMessage());
        }

        $arguments = is_a($throwable, GvException::class) ? $throwable->getArguments() : [];
        $this->logger->err($throwable->getMessage(), $arguments);
    }
}
