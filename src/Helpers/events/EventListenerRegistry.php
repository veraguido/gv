<?php
namespace Gvera\Helpers\events;

use Gvera\Events\QWEEvent;
use Gvera\Events\UserRegisteredEvent;
use Gvera\Listeners\QWEListener;
use Gvera\Listeners\UserRegisteredEmailListener;

/**
 * Class EventListenerRegistry
 * @package Gvera\Helpers\events
 * All event listeners should be added to this registry
 * @Inject userRegisteredEmailListener
 */
class EventListenerRegistry
{
    public function registerEventListeners()
    {
        EventDispatcher::addEventListener(
            UserRegisteredEvent::USER_REGISTERED_EVENT,
            $this->userRegisteredEmailListener
        );
    }
}
