<?php

namespace Gvera\Helpers\bootstrap;

use Gvera\Events\ThrowableFiredEvent;
use Gvera\Events\UserRegisteredEvent;
use Gvera\Helpers\dependencyInjection\DIContainer;
use Gvera\Helpers\events\EventListenerRegistry;
use ReflectionException;

class GvEventListenerRegistry extends EventListenerRegistry
{
    /**
     * @var DIContainer
     */
    private DIContainer $container;

    public function __construct(DIContainer $container)
    {
        $this->container = $container;
    }

    /**
     * @throws ReflectionException
     */
    public function registerEventListeners()
    {
        $this->registerEventListener(
            ThrowableFiredEvent::THROWABLE_FIRED_EVENT,
            $this->container->get('throwableListener')
        );

        $this->registerEventListener(
            UserRegisteredEvent::USER_REGISTERED_EVENT,
            $this->container->get('userRegisteredEmailListener')
        );
    }
}
