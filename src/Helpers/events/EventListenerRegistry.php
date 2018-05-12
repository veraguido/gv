<?php
namespace Gvera\Helpers\events;

use Gvera\Events\QWEEvent;
use Gvera\Events\UserRegisteredEvent;
use Gvera\Listeners\QWEListener;
use Gvera\Listeners\UserRegisteredEmailListener;
use Gvera\Events\ThrowableFiredEvent;

/**
 * Class EventListenerRegistry
 * @package Gvera\Helpers\events
 * All event listeners should be added to this registry
 * @Inject userRegisteredEmailListener
 * @Inject throwableListener
 */
class EventListenerRegistry
{
    public function registerEventListeners()
    {
        EventDispatcher::addEventListener(
            UserRegisteredEvent::USER_REGISTERED_EVENT,
            $this->userRegisteredEmailListener
        );

        EventDispatcher::addEventListener(
            ThrowableFiredEvent::THROWABLE_FIRED_EVENT,
            $this->throwableListener
        );
    }
}
