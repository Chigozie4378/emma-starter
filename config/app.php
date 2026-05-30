<?php

return [
    'name' => 'Emma Starter',
    'url' => 'http://localhost:8002',
    'env' => 'local',
    'debug' => true,

    /*
    | Global middleware runs before normal route middleware.
    | MacAccessMiddleware is harmless while config/mac_access.php enabled=false.
    */
    'global_middlewares' => [
        'MacAccessMiddleware',
    ]
];
