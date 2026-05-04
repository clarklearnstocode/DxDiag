<?php

require_once __DIR__ . '/../app/services/Mailer.php';

$result  = null;
$sent_to = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $to_email = trim($_POST['to_email'] ?? '');
    $to_name  = trim($_POST['to_name']  ?? 'Test User');
    $test_otp = '847291';   // fixed test code so you know what to look for

    if (filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
        $result  = Mailer::sendOTP($to_email, $to_name, $test_otp, 'Test');
        $sent_to = $to_email;
    } else {
        $result = 'Please enter a valid email address.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook — Email Test</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #c9a07a; }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { background:#080808; color:white; font-family:'Inter',sans-serif;
               display:flex; align-items:center; justify-content:center; min-height:100vh; padding:20px; }
        .card { background:#111; border:1px solid #1e1e1e; border-radius:18px; padding:40px; width:100%; max-width:480px; }
        h1 { font-size:1.4rem; margin-bottom:6px; }
        .sub { color:#555; font-size:0.85rem; margin-bottom:28px; line-height:1.6; }
        label { display:block; font-size:0.7rem; color:#555; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:7px; font-weight:600; }
        input { width:100%; background:#1a1a1a; border:1px solid #252525; padding:13px 14px;
                border-radius:10px; color:white; font-size:0.9rem; font-family:'Inter',sans-serif;
                outline:none; margin-bottom:16px; transition:0.2s; }
        input:focus { border-color:var(--primary); }
        button { width:100%; padding:14px; background:var(--primary); border:none; border-radius:10px;
                 font-weight:800; cursor:pointer; color:#000; font-family:'Inter',sans-serif; font-size:0.9rem; transition:0.2s; }
        button:hover { opacity:0.85; }

        .result { margin-top:22px; padding:16px; border-radius:11px; font-size:0.875rem; line-height:1.6; }
        .ok  { background:rgba(46,204,113,0.09); color:#2ecc71; border:1px solid rgba(46,204,113,0.2); }
        .err { background:rgba(231,76,60,0.09);  color:#e74c3c; border:1px solid rgba(231,76,60,0.2); }

        .config-box { background:#0d0d0d; border:1px solid #1e1e1e; border-radius:10px; padding:16px; margin-bottom:22px; font-size:0.8rem; }
        .config-box h3 { font-size:0.75rem; color:var(--primary); text-transform:uppercase; letter-spacing:1px; margin-bottom:10px; }
        .cfg-row { display:flex; justify-content:space-between; padding:5px 0; border-bottom:1px solid #161616; }
        .cfg-row:last-child { border-bottom:none; }
        .cfg-key { color:#444; }
        .cfg-val { color:white; font-family:'Courier New',monospace; }
        .cfg-warn { color:#e74c3c; }
        .cfg-ok   { color:#2ecc71; }

        .note { margin-top:16px; padding:12px 14px; background:rgba(231,76,60,0.07); border:1px solid rgba(231,76,60,0.15); border-radius:9px; font-size:0.75rem; color:#c0392b; }
    </style>
</head>
<body>
<div class="card">

    <h1>📧 Email Test Tool</h1>
    <p class="sub">Use this to verify your Gmail SMTP is configured correctly before testing the login flow. The test code it sends is always <strong style="color:var(--primary);">847291</strong>.</p>

    <!-- Show current config (masked password) -->
    <div class="config-box">
        <h3>Current Mailer.php Configuration</h3>
        <?php
            $host = Mailer::SMTP_HOST;
            $port = Mailer::SMTP_PORT;
            $from = Mailer::SMTP_FROM;
            $pass = Mailer::SMTP_PASS;

            $isDefaultFrom = ($from === 'your_gmail@gmail.com');
            $isDefaultPass = ($pass === 'xxxx xxxx xxxx xxxx');
            $passDisplay   = strlen($pass) > 4 ? substr($pass, 0, 4) . str_repeat('·', strlen($pass) - 4) : '(empty)';
        ?>
        <div class="cfg-row"><span class="cfg-key">SMTP_HOST</span><span class="cfg-val"><?php echo $host; ?></span></div>
        <div class="cfg-row"><span class="cfg-key">SMTP_PORT</span><span class="cfg-val"><?php echo $port; ?></span></div>
        <div class="cfg-row">
            <span class="cfg-key">SMTP_FROM</span>
            <span class="cfg-val <?php echo $isDefaultFrom ? 'cfg-warn' : 'cfg-ok'; ?>">
                <?php echo $isDefaultFrom ? '⚠ Not set' : htmlspecialchars($from); ?>
            </span>
        </div>
        <div class="cfg-row">
            <span class="cfg-key">SMTP_PASS</span>
            <span class="cfg-val <?php echo $isDefaultPass ? 'cfg-warn' : 'cfg-ok'; ?>">
                <?php echo $isDefaultPass ? '⚠ Not set' : htmlspecialchars($passDisplay); ?>
            </span>
        </div>
    </div>

    <?php if ($isDefaultFrom || $isDefaultPass): ?>
        <div class="result err" style="margin-bottom:20px;">
            ⚠ <strong>SMTP_FROM or SMTP_PASS is still at its placeholder value.</strong><br>
            Open <code>app/services/Mailer.php</code> and fill in your Gmail address and App Password first.
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Send test email to:</label>
        <input type="email" name="to_email"
               placeholder="ksabordo22@gmail.com"
               value="<?php echo htmlspecialchars($_POST['to_email'] ?? ''); ?>"
               required>
        <label>Your name (for the greeting):</label>
        <input type="text" name="to_name"
               placeholder="Clark"
               value="<?php echo htmlspecialchars($_POST['to_name'] ?? ''); ?>">
        <button type="submit">Send Test Email</button>
    </form>

    <?php if ($result !== null): ?>
        <?php if ($result === true): ?>
            <div class="result ok">
                ✓ <strong>Email sent successfully to <?php echo htmlspecialchars($sent_to); ?>!</strong><br>
                Check your inbox (and spam folder). The code in the email will be <strong style="color:var(--primary);">847291</strong>.<br><br>
                If it arrived → your 2FA login is fully working. Delete this file before going live.
            </div>
        <?php else: ?>
            <div class="result err">
                ✗ <strong>Failed to send:</strong><br>
                <code><?php echo htmlspecialchars((string)$result); ?></code><br><br>
                <strong>Common fixes:</strong><br>
                • Make sure 2-Step Verification is ON in your Google account<br>
                • Make sure you used an <strong>App Password</strong>, not your regular Gmail password<br>
                • App Password must have no spaces when pasted (remove the spaces Google adds)<br>
                • Check that your XAMPP has OpenSSL enabled in php.ini
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="note">🗑 Delete <code>test_email.php</code> before submitting or going live.</div>
</div>
</body>
</html>
