<?php

class Auth
{
    public static function check()
    {
        return Session::has('user');
    }

    public static function user()
    {
        return Session::get('user');
    }

    public static function login(array $user)
    {
        Session::put('user', $user);
    }

    public static function logout()
    {
        Session::forget('user');
    }

    public static function id()
    {
        $user = self::user();
        return $user['id'] ?? null;
    }

    public static function name()
    {
        $user = self::user();
        return $user['name'] ?? 'Guest';
    }
}