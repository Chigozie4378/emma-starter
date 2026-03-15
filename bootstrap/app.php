<?php

require_once dirname(__DIR__) . '/app/Helpers/helpers.php';
require_once app_path('Core/Session.php');
require_once app_path('Core/App.php');

App::boot();

$router = new Router();

require base_path('routes/web.php');

return $router;