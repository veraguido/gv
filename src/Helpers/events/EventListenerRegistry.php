<?php
/**
 * Created by PhpStorm.
 * User: guido
 * Date: 21/11/17
 * Time: 16:26
 */

namespace Gvera\Helpers\events;


use Gvera\Events\QWEEvent;
use Gvera\Listeners\QWEListener;

class EventListenerRegistry
{
    public static function registerEventListeners() {
        EventDispatcher::addEventListener(QWEEvent::QWE_NAME, new QWEListener());
    }
}