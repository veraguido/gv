<?php

namespace Gvera\Controllers;


use Gvera\Cache\Cache;
use Gvera\Commands\CreateNewUserCommand;
use Gvera\Commands\LoginCommand;
use Gvera\Helpers\locale\Locale;
use Gvera\Helpers\session\Session;
use Gvera\Services\UserService;


class Examples extends GvController
{
    public function index()
    {
        //echo phpinfo();
        //$this->httpResponse->redirect("/Cars/tiju");
        //$this->httpResponse->notFound();
    }

    public function tiju()
    {
        //$this->viewParams = array('asd' => 'iiiiuuuuuuuuujjjuuu');

        if (!Cache::getCache()->exists('asd')) {
            Cache::getCache()->save('asd', 'test ameo 2');
        }

        echo Cache::getCache()->load('asd');
    }

    public function tan()
    {

        Session::set("asd", 1);
        Session::toString();
        $count = Session::get('count') ? Session::get('count') : 1;

        echo $count;

        Session::set('count', ++$count);
    }

    public function login()
    {
        $loginCommand = new LoginCommand(
            $this->httpRequest->getParameter('username'),
            $this->httpRequest->getParameter('password')
        );
        $loginCommand->execute();
    }

    public function logout()
    {
        $us = new UserService();
        $us->logout();
    }

    public function asd()
    {
        /*$user = $this->entityManager->find('Gvera\Models\UserModel', 1);
        echo '<pre>';
        var_dump($user);
        echo '</pre>';*/
        //echo print_r(Session::get('user'));

    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function qwe()
    {
        /*echo '<pre>';
        var_dump(EntityManager::getInstance()->getRepository(User::class)->find(1)->getPassword());
        echo '</pre>';*/

        if($this->httpRequest->isPost()) {
            $registerUserCommand = new CreateNewUserCommand(
                $this->httpRequest->getParameter('username'),
                $this->httpRequest->getParameter('password'),
                $this->httpRequest->getParameter('email')
            );

            $registerUserCommand->execute();
            echo 'worked!';
        }


    }

    public function lorep() {
        echo Locale::getLocale("Hello world");
    }

    public function ipsum() {
        throw new \Exception('Test Exception for default controller');
    }
}