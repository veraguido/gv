<?php
namespace Gvera\Helpers\events;

use Gvera\Events\ThrowableFiredEvent;
use Gvera\Events\UserRegisteredEvent;
use Gvera\Helpers\dependencyInjection\DIContainer;
use Gvera\Listeners\EventListenerInterface;

/**
 * Class EventListenerRegistry
 * @package Gvera\Helpers\events
 * All event listeners should be added to this registry
 */
class EventListenerRegistry
{
    /**
     * @var DIContainer
     */
    private DIContainer $container;

    /**
     * EventListenerRegistry constructor.
     * @param DIContainer $container
     */
    public function __construct(DIContainer $container)
    {
        $this->container = $container;
    }

    /**
     * @throws \ReflectionException
     */
    public function registerEventListeners()
    {
        $this->registerEventListener(
            UserRegisteredEvent::USER_REGISTERED_EVENT,
            $this->container->get('userRegisteredEmailListener')
        );
        $this->registerEventListener(
            ThrowableFiredEvent::THROWABLE_FIRED_EVENT,
            $this->container->get('throwableListener')
        );
    }

    private function registerEventListener(string $eventId, EventListenerInterface $listener)
    {
        EventDispatcher::addEventListener($eventId, $listener);
    }
}
