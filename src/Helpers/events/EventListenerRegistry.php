<?php

namespace Gvera\Helpers\events;


use Gvera\Events\QWEEvent;
use Gvera\Listeners\QWEListener;

/**
 * Class EventListenerRegistry
 * @package Gvera\Helpers\events
 * All event listeners should be added to this registry
 */
class EventListenerRegistry
{
    public static function registerEventListeners() {
        EventDispatcher::addEventListener(QWEEvent::QWE_NAME, new QWEListener());
    }
}