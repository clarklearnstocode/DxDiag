<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook | Set Up Two-Factor Authentication</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link rel="stylesheet" href="assets/css/setup-totp.css">
</head>
<body>
<div id="setupTotpData" data-otpauth-uri="<?php echo htmlspecialchars($otpauthUri, ENT_QUOTES, 'UTF-8'); ?>"></div>
<div class="wrap">
    <div class="logo">Estate<span>Book</span></div>
    <div class="card">

        <h2>Set Up Two-Factor Authentication</h2>
        <p class="sub">
            Protect your <?php echo $role === 'admin' ? 'admin account' : 'account'; ?> with
            Google Authenticator. This is a one-time setup.
        </p>

        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-text">
                    <strong>Install an authenticator app</strong> on your phone:
                    <div class="app-row">
                        <span class="app-tag">📱 Google Authenticator</span>
                        <span class="app-tag">📱 Microsoft Authenticator</span>
                        <span class="app-tag">📱 Authy</span>
                    </div>
                </div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-text">
                    <strong>Open the app → tap "+" → "Scan a QR code"</strong>
                    and point your camera at the QR code below.
                </div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-text">
                    <strong>Enter the 6-digit code</strong> the app shows to confirm setup.
                </div>
            </div>
        </div>

        <!-- QR Code — rendered by qrcode.js entirely in the browser (no external API) -->
        <div class="qr-card">
            <div id="qrcode"></div>
            <p class="qr-label">Scan with your authenticator app</p>
        </div>

        <!-- Manual key fallback -->
        <div class="key-wrap">
            <span class="key-text" id="secretKey"><?php echo htmlspecialchars($secret); ?></span>
            <button class="copy-btn" onclick="copyKey(this)">Copy</button>
        </div>
        <p class="key-hint">
            Can't scan the QR? In your app tap <strong class="setup-key-highlight">"Enter a setup key"</strong>
            and paste the key above.
        </p>

        <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid'): ?>
            <div class="alert alert-error">
                ✗ Incorrect code — try again. Make sure your phone's time is accurate
                and that you scanned the right QR code.
            </div>
        <?php endif; ?>

        <hr>

        <form action="index.php?action=confirm_totp_setup" method="POST" id="setupForm">
            <input type="hidden" name="role"      value="<?php echo htmlspecialchars($role); ?>">
            <input type="hidden" name="secret"    value="<?php echo htmlspecialchars($secret); ?>">

            <label class="field-label">Enter the 6-digit code from your app to confirm</label>
            <input type="text" name="totp_code" id="totpInput"
                   class="verify-input" placeholder="000000"
                   maxlength="6" inputmode="numeric"
                   pattern="[0-9]{6}" autocomplete="one-time-code" required>

            <button type="submit" class="btn-confirm" id="confirmBtn" disabled>
                ✓ Confirm &amp; Activate 2FA
            </button>
        </form>

        <?php
            $skipUrl = $role === 'admin'
                ? 'index.php?action=admin_dashboard'
                : 'index.php?action=dashboard';
        ?>
        <a href="<?php echo $skipUrl; ?>" class="skip-link">Skip for now (not recommended)</a>
    </div>
</div>

<script src="assets/js/setup-totp.js"></script>
</body>
</html>
