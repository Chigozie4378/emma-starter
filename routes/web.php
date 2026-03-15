<?php

$router->get('/', 'HomeController@index', ['AuthMiddleware']);

$router->get('/login', 'AuthController@showLogin', ['GuestMiddleware']);
$router->post('/login', 'AuthController@login', ['GuestMiddleware', 'CsrfMiddleware']);
$router->post('/logout', 'AuthController@logout', ['AuthMiddleware', 'CsrfMiddleware']);