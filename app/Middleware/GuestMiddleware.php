<?php

class GuestMiddleware
{
    public function handle()
    {
        if (Auth::check()) {
            Response::redirect('/');
        }
    }
}