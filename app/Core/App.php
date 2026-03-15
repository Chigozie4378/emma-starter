<?php

class App
{
    public static function boot()
    {
        Session::start();

        require_once app_path('Core/Response.php');
        require_once app_path('Core/Request.php');
        require_once app_path('Core/Csrf.php');
        require_once app_path('Core/Validator.php');
        require_once app_path('Core/Database.php');
        require_once app_path('Core/Model.php');
        require_once app_path('Core/Auth.php');
        require_once app_path('Core/Controller.php');
        require_once app_path('Core/Router.php');
    }
}