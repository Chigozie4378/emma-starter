# Emma Starter

A reusable mini Laravel-style PHP MVC starter scaffold.

## Features
- MVC structure
- Router
- Controllers
- Models
- Database
- Sessions
- CSRF protection
- Middleware
- Auth
- Validation
- Layouts and partials
- Assets structure

## Run
```bash
php -S localhost:8000 -t public
```

## MAC Address Access Gate

This starter includes a config-driven MAC authorization gate that works with the provided local Python helper.

### Files added
- `config/mac_access.php`
- `app/Core/MacAccess.php`
- `app/Middleware/MacAccessMiddleware.php`
- `app/Controllers/MacAccessController.php`
- `resources/views/security/mac_gate.php`

### How it works
1. `MacAccessMiddleware` runs globally before route middleware.
2. If the gate is disabled, the app behaves exactly as before.
3. If enabled, the user first sees the MAC authorization screen.
4. The browser calls the local Python helper at `http://127.0.0.1:5000/get-mac-address`.
5. PHP receives the MAC through `/mac-access/verify`, checks it against `config/mac_access.php`, and stores the verified MAC in the session.
6. The normal login/auth flow then continues unchanged.

### Turn it on/off programmatically

Open `config/mac_access.php` and change:

```php
$defaultMacAccessEnabled = false;
```

to:

```php
$defaultMacAccessEnabled = true;
```

You can also override it without editing the file by setting an environment variable:

```bash
MAC_ACCESS_ENABLED=true
```

or:

```bash
MAC_ACCESS_ENABLED=false
```

### Add allowed MAC addresses

In `config/mac_access.php`:

```php
'allowed_macs' => [
    'AA:BB:CC:DD:EE:FF',
    '11-22-33-44-55-66',
    'A1B2C3D4E5F6',
],
```

All three formats are accepted and normalized internally.

### Python helper

Start your local Python MAC server on each authorized computer before opening the app:

```bash
start_flask_server.bat
```

The helper must return JSON like:

```json
{"mac_address":"AA:BB:CC:DD:EE:FF"}
```

### Useful endpoint

Check current MAC-gate session state:

```text
/mac-access/status
```
