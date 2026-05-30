<?php

/*
| Set the default here, or override without editing code by setting:
| MAC_ACCESS_ENABLED=true / false
*/
$defaultMacAccessEnabled = true;
$envMacAccessEnabled = getenv('MAC_ACCESS_ENABLED');

return [
    /*
    |--------------------------------------------------------------------------
    | MAC Access Gate
    |--------------------------------------------------------------------------
    | Turn this on/off from code/config only. No UI is provided.
    |
    | true  = every web route is blocked until the computer MAC address is verified.
    | false = the framework behaves exactly like before.
    */
    'enabled' => $envMacAccessEnabled === false
        ? $defaultMacAccessEnabled
        : in_array(strtolower((string) $envMacAccessEnabled), ['1', 'true', 'yes', 'on'], true),

    /*
    | Add only the MAC addresses you trust.
    | Accepted formats: AA:BB:CC:DD:EE:FF, AA-BB-CC-DD-EE-FF, or AABBCCDDEEFF.
    */
    'allowed_macs' => [
        // 'AA:BB:CC:DD:EE:FF',
        '50C2E8EA27E6',
    ],

    /*
    | Your Python helper runs on the client machine and exposes this endpoint.
    | The browser reads it, then sends the detected MAC to PHP for verification.
    */
    'client_mac_urls' => [
        'http://127.0.0.1:5000/get-mac-address',
        'http://localhost:5000/get-mac-address',
    ],

    /*
    | Routes that must remain reachable so the MAC gate can verify the device.
    | Use exact paths or wildcard prefixes like /assets/*.
    */
    'except' => [
        '/mac-access/verify',
        '/mac-access/status',
        '/assets/*',
        '/favicon.ico',
    ],

    'session_key' => 'mac_access_verified_mac',
    'verified_at_key' => 'mac_access_verified_at',

    'messages' => [
        'title' => 'System Authorization Required',
        'checking' => 'Checking this computer authorization...',
        'server_not_found' => 'This Device is not authorize to access the system',
        'not_allowed' => 'This computer is not authorized to access this system.',
    ],
];
