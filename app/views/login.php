<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EstateBook | Welcome Back</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/login-page.css">
</head>
<body>

    <a href="index.php?action=landing" class="back-home">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        <span>Back to Home</span>
    </a>

    <div class="image-side"></div>

    <div class="form-side">
        <div class="form-container">
            <div class="logo" onclick="window.location.href='index.php?action=landing'">Estate<span>Book</span></div>
            
            <?php if(isset($_GET['success'])): ?>
                <p class="register-success">
                    Account created! Please sign in.
                </p>
            <?php endif; ?>

            <h2>Welcome Back</h2>
            <p class="subtitle">Enter your details to access your dashboard.</p>

            <form action="index.php?action=authenticate" method="POST">
                <div class="input-group">
                    <label>Username or Email</label>
                    <input type="text" name="login_identity" placeholder="clark_dev" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-login">Sign In</button>
            </form>

            <div class="footer-link">
                New to EstateBook? <a href="index.php?action=register">Create Account</a>
            </div>

            <div class="admin-switch-wrap">
                <p class="admin-switch-text">
                    Are you a staff member? 
                    <a href="index.php?action=admin_login" class="admin-switch-link">
                        Admin Portal →
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script src="assets/js/auth-transition.js"></script>
</body>
</html>