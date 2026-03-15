<?php

class CsrfMiddleware
{
    public function handle()
    {
        if (Request::isPost()) {
            $token = Request::input('_token');

            if (!Csrf::verify($token)) {
                Response::json([
                    'status' => false,
                    'message' => 'Invalid CSRF token.'
                ], 419);
            }
        }
    }
}