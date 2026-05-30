<?php

class MacAccess
{
    public static function enabled(): bool
    {
        return self::toBool(config('mac_access.enabled', false));
    }

    public static function verified(): bool
    {
        if (!self::enabled()) {
            return true;
        }

        $mac = Session::get(config('mac_access.session_key', 'mac_access_verified_mac'));

        return $mac && self::isAllowed($mac);
    }

    public static function verifyAndStore($mac): array
    {
        $normalized = self::normalize($mac);

        if (!$normalized) {
            return [
                'status' => false,
                'message' => 'Invalid MAC address format.',
                'mac_address' => null,
            ];
        }

        if (!self::isAllowed($normalized)) {
            return [
                'status' => false,
                'message' => config('mac_access.messages.not_allowed', 'This computer is not authorized.'),
                'mac_address' => $normalized,
            ];
        }

        Session::put(config('mac_access.session_key', 'mac_access_verified_mac'), $normalized);
        Session::put(config('mac_access.verified_at_key', 'mac_access_verified_at'), date('Y-m-d H:i:s'));

        return [
            'status' => true,
            'message' => 'Device authorized.',
            'mac_address' => $normalized,
        ];
    }

    public static function clear(): void
    {
        Session::forget(config('mac_access.session_key', 'mac_access_verified_mac'));
        Session::forget(config('mac_access.verified_at_key', 'mac_access_verified_at'));
    }

    public static function isAllowed($mac): bool
    {
        $normalized = self::normalize($mac);

        if (!$normalized) {
            return false;
        }

        $allowed = config('mac_access.allowed_macs', []);

        foreach ($allowed as $allowedMac) {
            if ($normalized === self::normalize($allowedMac)) {
                return true;
            }
        }

        return false;
    }

    public static function normalize($mac): ?string
    {
        $raw = strtoupper(preg_replace('/[^A-Fa-f0-9]/', '', (string) $mac));

        if (strlen($raw) !== 12 || !ctype_xdigit($raw)) {
            return null;
        }

        return implode(':', str_split($raw, 2));
    }

    public static function currentPath(): string
    {
        $path = parse_url(Request::uri(), PHP_URL_PATH) ?: '/';
        $path = rtrim($path, '/');

        return $path === '' ? '/' : $path;
    }

    public static function shouldSkipCurrentRequest(): bool
    {
        $currentPath = self::currentPath();

        foreach (config('mac_access.except', []) as $exceptPath) {
            $exceptPath = '/' . trim((string) $exceptPath, '/');
            $exceptPath = $exceptPath === '/' ? '/' : rtrim($exceptPath, '/');

            if (str_ends_with($exceptPath, '/*')) {
                $prefix = rtrim(substr($exceptPath, 0, -2), '/');

                if ($currentPath === $prefix || str_starts_with($currentPath, $prefix . '/')) {
                    return true;
                }

                continue;
            }

            if ($currentPath === $exceptPath) {
                return true;
            }
        }

        return false;
    }

    public static function renderGate(): void
    {
        http_response_code(403);

        $title = config('mac_access.messages.title', 'System Authorization Required');
        $checking = config('mac_access.messages.checking', 'Checking this computer authorization...');
        $serverNotFound = config('mac_access.messages.server_not_found', 'The local MAC server is not running.');
        $notAllowed = config('mac_access.messages.not_allowed', 'This computer is not authorized.');
        $clientUrls = config('mac_access.client_mac_urls', []);
        $csrfToken = Csrf::token();
        $currentUrl = Request::uri();

        require view_path('security.mac_gate');
        exit;
    }

    private static function toBool($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        return in_array(strtolower((string) $value), ['1', 'true', 'yes', 'on'], true);
    }
}
