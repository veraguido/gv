<?php

namespace Gvera\Helpers\events;


use Gvera\Events\Event;
use Gvera\Listeners\EventListener;

class EventDispatcher
{
    public static $eventsListeners = array();

    public static function addEventListener(string $eventId, EventListener $listener)
    {
        $listeners = (isset(self::$eventsListeners[$eventId]) && is_iterable(self::$eventsListeners[$eventId])) ? array_push(self::$eventsListeners[$eventId], $listener): array($listener);
        self::$eventsListeners[$eventId] = $listeners;
    }

    public static function dispatchEvent($eventId, Event $event)
    {
        $listeners = isset(self::$eventsListeners[$eventId]) ? self::$eventsListeners[$eventId] : array();

        foreach($listeners as $listener) {
            $listener->handleEvent($event);
        }
    }

    public static function removeEventListeners(string $eventId){
        unset(self::$eventsListeners[$eventId]);
    }

}