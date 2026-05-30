<?php
$escapedTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
$escapedChecking = htmlspecialchars($checking, ENT_QUOTES, 'UTF-8');
$escapedServerNotFound = htmlspecialchars($serverNotFound, ENT_QUOTES, 'UTF-8');
$escapedNotAllowed = htmlspecialchars($notAllowed, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $escapedTitle ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: linear-gradient(135deg, #020617, #0f172a, #111827);
            color: #fff;
        }
        .mac-card {
            width: 100%;
            max-width: 520px;
            padding: 28px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.10);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
        }
        .mac-title { font-size: 28px; margin-bottom: 10px; }
        .mac-text { color: #cbd5e1; line-height: 1.6; margin-bottom: 18px; }
        .mac-status {
            padding: 14px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.10);
            color: #dbeafe;
            line-height: 1.5;
            margin-bottom: 16px;
        }
        .mac-error {
            background: rgba(239, 68, 68, 0.12);
            border-color: rgba(239, 68, 68, 0.28);
            color: #fecaca;
        }
        .mac-success {
            background: rgba(34, 197, 94, 0.12);
            border-color: rgba(34, 197, 94, 0.28);
            color: #bbf7d0;
        }
        .mac-button {
            border: none;
            border-radius: 14px;
            padding: 13px 18px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
        }
        .mac-help { margin-top: 18px; color: #94a3b8; font-size: 14px; line-height: 1.6; }
        code {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.09);
            color: #e0f2fe;
        }
    </style>
</head>
<body>
    <main class="mac-card">
        <h1 class="mac-title"><?= $escapedTitle ?></h1>
        <!-- <p class="mac-text">This system is protected. Only computers with approved MAC addresses can continue.</p> -->
        <div id="macStatus" class="mac-status"><?= $escapedChecking ?></div>
        <button type="button" class="mac-button" onclick="verifyMacAccess()">Try Again</button>
        <!-- <p class="mac-help">
            If this keeps failing, start your Python helper first, for example:
            <code>start_flask_server.bat</code>
        </p> -->
    </main>

    <script>
        const macAccessConfig = {
            clientUrls: <?= json_encode(array_values($clientUrls), JSON_UNESCAPED_SLASHES) ?>,
            verifyUrl: '/mac-access/verify',
            csrfToken: <?= json_encode($csrfToken) ?>,
            currentUrl: <?= json_encode($currentUrl) ?>,
            messages: {
                checking: <?= json_encode($checking) ?>,
                serverNotFound: <?= json_encode($serverNotFound) ?>,
                notAllowed: <?= json_encode($notAllowed) ?>,
            }
        };

        function setMacStatus(message, type = '') {
            const box = document.getElementById('macStatus');
            box.textContent = message;
            box.className = 'mac-status' + (type ? ' mac-' + type : '');
        }

        async function fetchWithTimeout(url, timeoutMs = 2500) {
            const controller = new AbortController();
            const timer = setTimeout(() => controller.abort(), timeoutMs);

            try {
                const response = await fetch(url, {
                    method: 'GET',
                    cache: 'no-store',
                    signal: controller.signal
                });
                return response;
            } finally {
                clearTimeout(timer);
            }
        }

        async function getClientMacAddress() {
            for (const url of macAccessConfig.clientUrls) {
                try {
                    const response = await fetchWithTimeout(url);

                    if (!response.ok) {
                        continue;
                    }

                    const data = await response.json();

                    if (data && data.mac_address) {
                        return data.mac_address;
                    }
                } catch (error) {
                    // Try the next URL.
                }
            }

            return null;
        }

        async function verifyMacAccess() {
            setMacStatus(macAccessConfig.messages.checking);

            const macAddress = await getClientMacAddress();

            if (!macAddress) {
                setMacStatus(macAccessConfig.messages.serverNotFound, 'error');
                return;
            }

            try {
                const body = new URLSearchParams();
                body.set('_token', macAccessConfig.csrfToken);
                body.set('mac_address', macAddress);

                const response = await fetch(macAccessConfig.verifyUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: body.toString()
                });

                const result = await response.json();

                if (response.ok && result.status) {
                    setMacStatus('Computer authorized. Opening system...', 'success');
                    window.location.href = macAccessConfig.currentUrl || '/';
                    return;
                }

                setMacStatus((result && result.message) ? result.message : macAccessConfig.messages.notAllowed, 'error');
            } catch (error) {
                setMacStatus('Unable to verify this computer. Please try again.', 'error');
            }
        }

        verifyMacAccess();
    </script>
</body>
</html>
