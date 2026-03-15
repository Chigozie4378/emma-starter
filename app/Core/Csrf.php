<?php

class Csrf
{
    public static function token()
    {
        if (!Session::has('_csrf_token')) {
            Session::put('_csrf_token', bin2hex(random_bytes(32)));
        }

        return Session::get('_csrf_token');
    }

    public static function verify($token)
    {
        $sessionToken = Session::get('_csrf_token');

        if (!$sessionToken || !$token) {
            return false;
        }

        return hash_equals($sessionToken, $token);
    }

    public static function field()
    {
        return '<input type="hidden" name="_token" value="' . htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8') . '">';
    }
}