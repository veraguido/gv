<?php
namespace Gvera\Helpers\session;

/**
 * Class Session
 * @package Gvera\Helpers\session
 * A session wrapper
 */
class Session
{

    private $started = false;

    public function getId()
    {
        return session_id();
    }

    private function start()
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
            $this->started = true;
        }
    }

    public function set($key, $value)
    {
        $this->start();
        $_SESSION[$key] = $value;
    }

    public function unset($key)
    {
        $this->start();
        unset($_SESSION[$key]);
    }

    /**
     * @return mixed
     */
    public function get($key)
    {
        $this->start();
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }

        return false;
    }

    public function destroy()
    {
        if ($this->started) {
            session_destroy();
            $this->started = false;
        }
    }

    public function toString()
    {
        echo '<pre>';
        print_r($_SESSION);
        echo '</pre>';
    }
}
