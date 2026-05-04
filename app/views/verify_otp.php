<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook | Verify Your Identity</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #c9a07a; --dark: #0a0a0a; --card: #111; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: var(--dark);
            color: white;
            font-family: 'Inter', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .card {
            background: var(--card);
            border: 1px solid #1e1e1e;
            border-radius: 22px;
            padding: 44px 40px;
            width: 100%;
            max-width: 420px;
            text-align: center;
        }

        /* Shield icon */
        .shield-wrap {
            width: 68px; height: 68px;
            background: rgba(201,160,122,0.1);
            border: 1px solid rgba(201,160,122,0.2);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 22px;
            animation: fadeIn 0.4s ease both;
        }
        @keyframes fadeIn { from { opacity:0; transform:scale(0.8); } to { opacity:1; transform:scale(1); } }

        .logo { font-weight: 800; font-size: 1.3rem; margin-bottom: 6px; }
        .logo span { color: var(--primary); }

        h2 { font-size: 1.4rem; font-weight: 800; margin-bottom: 8px; letter-spacing: -0.3px; }
        .desc { color: #555; font-size: 0.875rem; line-height: 1.6; margin-bottom: 28px; }
        .desc strong { color: #888; }

        /* Alert banners */
        .alert { padding: 11px 16px; border-radius: 9px; margin-bottom: 20px; font-size: 0.82rem; font-weight: 600; text-align: left; display: flex; align-items: flex-start; gap: 9px; }
        .alert-error   { background: rgba(231,76,60,0.09);  color: #e74c3c; border: 1px solid rgba(231,76,60,0.2); }
        .alert-warning { background: rgba(241,196,15,0.09); color: #f1c40f; border: 1px solid rgba(241,196,15,0.2); }
        .alert-info    { background: rgba(201,160,122,0.09); color: var(--primary); border: 1px solid rgba(201,160,122,0.2); }

        /* OTP input row — 6 individual digit boxes */
        .otp-row {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 24px;
        }
        .otp-box {
            width: 48px; height: 58px;
            background: #1a1a1a;
            border: 2px solid #252525;
            border-radius: 11px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            font-family: 'Courier New', monospace;
            outline: none;
            transition: border-color 0.2s;
            caret-color: var(--primary);
        }
        .otp-box:focus { border-color: var(--primary); background: #1f1f1f; }
        .otp-box.filled { border-color: rgba(201,160,122,0.4); }

        /* Hidden real input for form submission */
        #otp_code { display: none; }

        .btn-verify {
            width: 100%;
            padding: 15px;
            background: var(--primary);
            border: none;
            border-radius: 11px;
            font-weight: 800;
            font-size: 0.9rem;
            color: #000;
            cursor: pointer;
            transition: 0.25s;
            font-family: 'Inter', sans-serif;
            margin-bottom: 16px;
        }
        .btn-verify:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(201,160,122,0.25); }
        .btn-verify:disabled { opacity: 0.35; cursor: not-allowed; }

        /* Countdown */
        .countdown-wrap { margin-bottom: 18px; }
        .countdown-label { font-size: 0.75rem; color: #333; margin-bottom: 6px; }
        .countdown-bar-bg { background: #1a1a1a; border-radius: 20px; height: 4px; overflow: hidden; }
        .countdown-bar    { background: var(--primary); height: 100%; border-radius: 20px; transition: width 1s linear; }
        .countdown-num    { font-size: 0.78rem; color: #555; margin-top: 6px; }
        .countdown-num span { color: var(--primary); font-weight: 700; }

        .divider { border: none; border-top: 1px solid #1a1a1a; margin: 18px 0; }

        /* Resend */
        .resend-wrap { font-size: 0.82rem; color: #444; }
        .resend-wrap a, .resend-wrap button {
            color: var(--primary); font-weight: 700; background: none;
            border: none; cursor: pointer; font-family: inherit; font-size: inherit;
            text-decoration: none; transition: 0.2s;
        }
        .resend-wrap a:hover, .resend-wrap button:hover { opacity: 0.75; }

        .back-link { display: block; margin-top: 18px; color: #333; font-size: 0.8rem; text-decoration: none; transition: 0.2s; }
        .back-link:hover { color: #666; }

        /* Role badge */
        .role-badge {
            display: inline-block;
            background: rgba(201,160,122,0.1);
            color: var(--primary);
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 18px;
        }
    </style>
</head>
<body>
<div class="card">

    <!-- Shield icon -->
    <div class="shield-wrap">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#c9a07a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            <polyline points="9 12 11 14 15 10"/>
        </svg>
    </div>

    <div class="logo">Estate<span>Book</span></div>
    <span class="role-badge"><?php echo $role === 'admin' ? 'Admin Verification' : 'Account Verification'; ?></span>

    <h2>Check Your Email</h2>
    <p class="desc">
        We sent a <strong>6-digit code</strong> to your registered email address.<br>
        Enter it below to continue.
    </p>

    <!-- Error alert -->
    <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid'): ?>
        <div class="alert alert-error">
            <span>✗</span>
            <span>Incorrect or expired code. Please try again or request a new one.</span>
        </div>
    <?php endif; ?>

    <!-- Mail error alert (SMTP not configured yet) -->
    <?php if (isset($_GET['mail_error']) && $_GET['mail_error'] === '1'): ?>
        <div class="alert alert-warning">
            <span>⚠</span>
            <div>
                Email could not be sent — SMTP not configured yet.<br>
                <strong>For now, use the development bypass below.</strong><br>
                <small style="color:#666;font-weight:400;">See <code>app/services/Mailer.php</code> to set up Gmail.</small>
            </div>
        </div>
    <?php endif; ?>

    <!-- Countdown timer -->
    <div class="countdown-wrap" id="countdownWrap">
        <div class="countdown-label">Code expires in</div>
        <div class="countdown-bar-bg">
            <div class="countdown-bar" id="countdownBar" style="width:100%;"></div>
        </div>
        <div class="countdown-num">
            <span id="countdownNum"><?php echo $secondsLeft; ?></span>s remaining
        </div>
    </div>

    <!-- OTP form -->
    <form action="index.php?action=handle_verify_otp" method="POST" id="otpForm">
        <input type="hidden" name="role"     value="<?php echo htmlspecialchars($role); ?>">
        <input type="hidden" name="otp_code" id="otp_code">

        <div class="otp-row" id="otpRow">
            <?php for ($i = 0; $i < 6; $i++): ?>
                <input type="text" class="otp-box" maxlength="1"
                       inputmode="numeric" pattern="[0-9]"
                       data-index="<?php echo $i; ?>" autocomplete="off">
            <?php endfor; ?>
        </div>

        <button type="submit" class="btn-verify" id="verifyBtn" disabled>
            Verify & Continue
        </button>
    </form>

    <hr class="divider">

    <!-- Resend -->
    <div class="resend-wrap">
        Didn't receive it?
        <a href="<?php echo $role === 'admin' ? 'index.php?action=admin_login' : 'index.php?action=login'; ?>">
            Go back and try again
        </a>
    </div>

    <a href="<?php echo $role === 'admin' ? 'index.php?action=admin_login' : 'index.php?action=login'; ?>"
       class="back-link">← Back to login</a>
</div>

<script>
// ── OTP box auto-advance ──
var boxes   = document.querySelectorAll('.otp-box');
var hidden  = document.getElementById('otp_code');
var btn     = document.getElementById('verifyBtn');

function updateHidden() {
    var val = Array.from(boxes).map(b => b.value).join('');
    hidden.value = val;
    btn.disabled = val.length < 6;
    boxes.forEach(function(b) {
        b.classList.toggle('filled', b.value !== '');
    });
}

boxes.forEach(function(box, idx) {
    box.addEventListener('input', function(e) {
        // Allow pasting full code into first box
        var val = e.target.value.replace(/\D/g, '');
        if (val.length > 1) {
            val.split('').forEach(function(ch, i) {
                if (boxes[idx + i]) boxes[idx + i].value = ch;
            });
            var next = idx + val.length;
            if (boxes[next]) boxes[next].focus();
        } else {
            box.value = val;
            if (val && boxes[idx + 1]) boxes[idx + 1].focus();
        }
        updateHidden();
    });

    box.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' && !box.value && boxes[idx - 1]) {
            boxes[idx - 1].focus();
        }
    });

    // Allow paste on any box
    box.addEventListener('paste', function(e) {
        e.preventDefault();
        var pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
        pasted.split('').forEach(function(ch, i) {
            if (boxes[i]) boxes[i].value = ch;
        });
        var lastFilled = Math.min(pasted.length, 5);
        boxes[lastFilled].focus();
        updateHidden();
    });
});

// Auto-focus first box
boxes[0] && boxes[0].focus();

// ── Countdown timer ──
var total   = <?php echo max(0, $secondsLeft); ?>;
var elapsed = 0;
var bar     = document.getElementById('countdownBar');
var num     = document.getElementById('countdownNum');

function tick() {
    elapsed++;
    var left = Math.max(0, total - elapsed);
    var pct  = total > 0 ? (left / total * 100) : 0;
    bar.style.width = pct + '%';
    num.textContent = left;

    if (left <= 0) {
        bar.style.background = '#e74c3c';
        num.style.color = '#e74c3c';
        btn.disabled = true;
        btn.textContent = 'Code Expired — Go back to re-login';
        clearInterval(timer);
    }
}
var timer = setInterval(tick, 1000);
</script>
</body>
</html>
