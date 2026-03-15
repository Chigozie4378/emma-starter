<?php

class AuthMiddleware
{
    public function handle()
    {
        if (!Auth::check()) {
            Response::redirect('/login');
        }
    }
}