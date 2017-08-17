<?php

namespace Gvera\Controllers;


use Doctrine\ORM\EntityManager;
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

        echo "asd! seeee";
    }

    public function qwe()
    {
        echo '<pre>';
        var_dump($this->entityManager->getRepository(User::class)->find(1)->getPassword());
        echo '</pre>';

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