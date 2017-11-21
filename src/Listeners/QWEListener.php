<?php
/**
 * Created by PhpStorm.
 * User: guido
 * Date: 21/11/17
 * Time: 16:25
 */

namespace Gvera\Listeners;


use Gvera\Events\Event;

class QWEListener implements EventListener
{

    public function handleEvent(Event $event)
    {
        echo 'QWEListener called, with obj:' . $event->getObjectData();
    }
}