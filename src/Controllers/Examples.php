<?php

namespace Gvera\Controllers;


use Doctrine\ORM\EntityManager;
use Gvera\Events\QWEEvent;
use Gvera\Helpers\config\Config;
use Gvera\Helpers\events\EventDispatcher;
use Gvera\Helpers\Session\Session;
use Gvera\Cache\RedisCache;
use Gvera\Models\User;
use Gvera\Models\UserStatus;

class Examples extends GvController
{
    public function index()
    {
        echo phpinfo();
        //$this->httpResponse->redirect("/Cars/tiju");
        //$this->httpResponse->notFound();
    }

    public function tiju()
    {
        //$this->viewParams = array('asd' => 'iiiiuuuuuuuuujjjuuu');
    }

    public function tan()
    {

        Session::set("asd", 1);
        Session::toString();
        $count = Session::get('count') ? Session::get('count') : 1;

        echo $count;

        Session::set('count', ++$count);
    }

    public function asd()
    {
        /*$user = $this->entityManager->find('Gvera\Models\UserModel', 1);
        echo '<pre>';
        var_dump($user);
        echo '</pre>';*/

        print_r($this->httpRequest->getParameters());

        print_r(Config::getInstance()->getConfig("mysql"));
    }

    public function qwe()
    {
        echo "first call, something happening, call to be made <br/>";
        $event = new QWEEvent(QWEEvent::QWE_NAME, 234);

        EventDispatcher::dispatchEvent(QWEEvent::QWE_NAME, $event);
        echo "<br />";

        echo "event dispatched, all done :), removing event listener now <br/>";

        EventDispatcher::removeAllListenersFromEvent(QWEEvent::QWE_NAME);

        echo '<br/> sending signal again';

        EventDispatcher::dispatchEvent(QWEEvent::QWE_NAME, $event);


        /*echo '<pre>';
        var_dump($this->entityManager->getRepository(User::class)->find(1)->getPassword());
        echo '</pre>';*/

        /*$status = new UserStatusModel();
        $status->setStatus('dsf');
        $this->entityManager->persist($status);
        $this->entityManager->flush();

        $user = new UserModel();
        $user->setUsername($this->httpRequest->getParameter('username'));
        $user->setPassword(password_hash($this->httpRequest->getParameter('pass'), PASSWORD_BCRYPT));
        $user->setCreated();
        $user->setStatus($status);

        $this->entityManager->persist($user);
        $this->entityManager->flush();*/

    }
}