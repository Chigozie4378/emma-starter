<?php

require_once app_path('Core/Controller.php');
require_once app_path('Core/Request.php');
require_once app_path('Core/Csrf.php');
require_once app_path('Core/MacAccess.php');

class MacAccessController extends Controller
{
    public function status()
    {
        $this->json([
            'status' => true,
            'enabled' => MacAccess::enabled(),
            'verified' => MacAccess::verified(),
            'mac_address' => Session::get(config('mac_access.session_key', 'mac_access_verified_mac')),
            'verified_at' => Session::get(config('mac_access.verified_at_key', 'mac_access_verified_at')),
        ]);
    }

    public function verify()
    {
        if (!Csrf::verify(Request::input('_token'))) {
            $this->json([
                'status' => false,
                'message' => 'Invalid CSRF token.',
            ], 419);
        }

        if (!MacAccess::enabled()) {
            $this->json([
                'status' => true,
                'message' => 'MAC access gate is disabled.',
                'enabled' => false,
            ]);
        }

        $result = MacAccess::verifyAndStore(Request::input('mac_address'));

        $this->json($result, $result['status'] ? 200 : 403);
    }
}
