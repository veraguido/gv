<?php
namespace Gvera\Helpers\events;

use Gvera\Events\Event;
use Gvera\Listeners\EventListenerInterface;

/**
 * Class EventDispatcher
 * @package Gvera\Helpers\events
 * Event listeners are registered through the dispatcher, and the callbacks are handled in this class as well.
 */
class EventDispatcher
{
    public static array $eventsListeners = array();

    public static function addEventListener(string $eventId, EventListenerInterface $listener)
    {
        $listeners = (isset(self::$eventsListeners[$eventId]) && is_iterable(self::$eventsListeners[$eventId])) ?
            array_push(self::$eventsListeners[$eventId], $listener) :
            array($listener);

        self::$eventsListeners[$eventId] = $listeners;
    }

    public static function dispatchEvent($eventId, Event $event)
    {
        $listeners = isset(self::$eventsListeners[$eventId]) ? self::$eventsListeners[$eventId] : array();

        foreach ($listeners as $listener) {
            $listener->handleEvent($event);
            if ($event->hasStopPropagation()) {
                break;
            }
        }
    }

    public static function removeEventListener(string $eventId)
    {
        unset(self::$eventsListeners[$eventId]);
    }
}
