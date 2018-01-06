<?php
/**
 * Created by PhpStorm.
 * User: guido
 * Date: 1/5/18
 * Time: 8:08 PM
 */

namespace Gvera\Listeners;


use Gvera\Events\Event;
use Gvera\Helpers\config\Config;
use Gvera\Helpers\email\GvEmail;

class UserRegisteredEmailListener implements EventListener
{

    public function handleEvent(Event $event)
    {

        if (boolval(Config::getInstance()->getConfig('devmode')) === false) {
            $username = $event->getUserName();
            $message = "Hi $username we want to let you know that your account is registered :)";
            $newUserEmail = new GvEmail(
                false,
                "gv account created",
                $message,
                $message
            );

            $newUserEmail->addAddress($event->getEmail());
            $newUserEmail->send();
        }
    }
}