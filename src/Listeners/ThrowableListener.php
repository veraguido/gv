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

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param $event
     * @return void
     */
    public function handleEvent($event)
    {
        $throwable = $event->getThrowable();
        $httpResponse = $event->getHttpResponse();
        if ($event->isDevMode()) {
            $httpResponse->terminate($event->getThrowable()->getMessage());
            return;
        }

        $arguments = is_a($throwable, GvException::class) ? $throwable->getArguments() : [];
        $this->logger->err($throwable->getMessage(), $arguments);

        $httpResponse->redirect('/');
    }
}
