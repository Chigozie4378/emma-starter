<?php

class Session
{
    public static function start()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public static function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function put($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function has($key)
    {
        return array_key_exists($key, $_SESSION);
    }

    public static function forget($key)
    {
        unset($_SESSION[$key]);
    }

    public static function flash($key, $value)
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public static function getFlash($key, $default = null)
    {
        $value = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }

    public static function destroy()
    {
        session_unset();
        session_destroy();
    }
}