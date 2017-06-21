<?php
namespace Gvera\Helpers\session;
class Session
{

    private static $started = false;

    public static function getId()
    {
        return session_id();
    }

    private static function start()
    {
        if (!self::$started)
        {
            self::$started = true;
            session_start();
        }
    }

    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        self::start();
        if (isset($_SESSION[$key])){
            return $_SESSION[$key];
        }

        return false;
    }

    public static function destroy()
    {
        if (self::$started)
        {
            session_destroy();
            self::$started = false;
        }
    }

    public static function toString()
    {
        echo '<pre>';
        print_r($_SESSION);
        echo '</pre>';
    }
}