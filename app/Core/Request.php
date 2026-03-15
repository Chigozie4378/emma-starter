<?php

class Request
{
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public static function input($key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    public static function all()
    {
        return array_merge($_GET, $_POST);
    }

    public static function only(array $keys)
    {
        $data = [];

        foreach ($keys as $key) {
            $data[$key] = self::input($key);
        }

        return $data;
    }

    public static function isPost()
    {
        return self::method() === 'POST';
    }

    public static function isAjax()
    {
        return strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
    }

    public static function uri()
    {
        return $_SERVER['REQUEST_URI'] ?? '/';
    }
}