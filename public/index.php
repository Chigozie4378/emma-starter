<?php

$router = require dirname(__DIR__) . '/bootstrap/app.php';

$router->dispatch(Request::uri(), Request::method());