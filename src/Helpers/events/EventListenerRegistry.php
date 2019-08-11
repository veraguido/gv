<?php
namespace Gvera\Helpers\events;

use Gvera\Events\ThrowableFiredEvent;
use Gvera\Events\UserRegisteredEvent;

/**
 * Class EventListenerRegistry
 * @package Gvera\Helpers\events
 * All event listeners should be added to this registry
 */
class EventListenerRegistry
{

    private $throwableListener;
    private $userRegisteredEmailListener;

    public function __construct($throwableListener, $userRegisteredEmailListener)
    {
        $this->throwableListener = $throwableListener;
        $this->userRegisteredEmailListener = $userRegisteredEmailListener;
    }

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
