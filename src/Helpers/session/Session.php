<?php
namespace Gvera\Helpers\session;

/**
 * Class Session
 * @package Gvera\Helpers\session
 * A session wrapper
 */
class Session
{
    const GV_SESSION_NAME = 'sgvid';

    private bool $started = false;

    public function getId()
    {
        return session_id();
    }

    private function start()
    {
        if (!$this->started) {
            session_name(self::GV_SESSION_NAME);
            session_start();
            $this->started = true;
        }
    }

    public function set($key, $value)
    {
        $this->start();
        $_SESSION[$key] = $value;
    }

    public function unsetByKey($key)
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
        $this->start();
        session_destroy();
        session_unset();
        unset($_SESSION);
        session_write_close();
    }

    public function toString()
    {
        echo '<pre>';
        print_r($_SESSION);
        echo '</pre>';
    }
}
