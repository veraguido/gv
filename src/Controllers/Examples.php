<?php

namespace Gvera\Controllers;


use Gvera\Helpers\Session\Session;
use Gvera\Cache\RedisCache;

class Examples extends GController
{
    public function index()
    {
        echo phpinfo();
        //$this->httpResponse->redirect("/Cars/tiju");
        //$this->httpResponse->notFound();
    }

    public function tiju()
    {
        $this->viewParams = array('asd' => 'iiiiuuuuuuuuujjjuuu');
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
        echo password_hash("asd", PASSWORD_BCRYPT);
    }

    public function qwe()
    {
        $redis = RedisCache::getInstance()->save("asd", "qwerty");
        echo (RedisCache::getInstance()->load("asd"));
    }
}