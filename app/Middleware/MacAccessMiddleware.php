<?php

class MacAccessMiddleware
{
    public function handle()
    {
        if (!MacAccess::enabled() || MacAccess::shouldSkipCurrentRequest()) {
            return;
        }

        if (MacAccess::verified()) {
            return;
        }

        if (Request::isAjax()) {
            Response::json([
                'status' => false,
                'message' => config('mac_access.messages.not_allowed', 'This computer is not authorized.'),
                'code' => 'MAC_ACCESS_REQUIRED',
            ], 403);
        }

        MacAccess::renderGate();
    }
}
