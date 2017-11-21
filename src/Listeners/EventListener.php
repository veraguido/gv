<?php

namespace Gvera\Listeners;


use Gvera\Events\Event;

interface EventListener
{
    public function handleEvent(Event $event);
}