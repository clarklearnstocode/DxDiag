<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook | Two-Factor Verification</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/verify-totp.css">
</head>
<body>
<div class="card">

    <div class="shield">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#c9a07a" stroke-width="2">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            <polyline points="9 12 11 14 15 10"/>
        </svg>
    </div>

    <div class="logo">Estate<span>Book</span></div>
    <span class="role-badge"><?php echo $role === 'admin' ? 'Admin Verification' : '2FA Verification'; ?></span>

    <h2>Authenticator Code</h2>
    <p class="sub">
        Open <strong>Google Authenticator</strong> on your phone and enter the
        6-digit code shown for <strong>EstateBook</strong>.
    </p>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid'): ?>
        <div class="alert alert-error">✗ Incorrect code. The code changes every 30 seconds — try the latest one.</div>
    <?php endif; ?>

    <form action="index.php?action=handle_verify_totp" method="POST" id="totpForm">
        <input type="hidden" name="role"     value="<?php echo htmlspecialchars($role); ?>">
        <input type="hidden" name="totp_code" id="totp_code_hidden">

        <div class="otp-row" id="otpRow">
            <?php for ($i = 0; $i < 6; $i++): ?>
                <input type="text" class="otp-box" maxlength="1"
                       inputmode="numeric" pattern="[0-9]"
                       data-index="<?php echo $i; ?>" autocomplete="off">
            <?php endfor; ?>
        </div>

        <p class="timer-hint">
            The code refreshes every <strong>30 seconds</strong>.<br>
            If the code just changed, wait for the next one.
        </p>

        <button type="submit" class="btn-verify" id="verifyBtn" disabled>Verify</button>
    </form>

    <hr>
    <a href="<?php echo $role === 'admin' ? 'index.php?action=admin_login' : 'index.php?action=login'; ?>"
       class="back-link">← Back to login</a>
</div>

<script src="assets/js/verify-totp.js"></script>
</body>
</html>
