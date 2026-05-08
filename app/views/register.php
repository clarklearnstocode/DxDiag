<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EstateBook | Join the Elite</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/register-page.css">
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
            <h2>Create Account</h2>
            <p class="subtitle">Join the elite community of Bacolod homeowners.</p>

            <form action="index.php?action=handleSignup" method="POST">
                <div class="input-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" placeholder="Clark Sabordo" required>
                </div>

                <div class="input-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="clark_dev" required>
                </div>

                <div class="input-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="clark@example.com" required>
                </div>

                <div class="input-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" placeholder="0912 345 6789" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-reg">Get Started</button>
            </form>

            <div class="footer-link">
                Already part of the elite? <a href="index.php?action=login">Sign In</a>
            </div>
        </div>
    </div>

    <script src="assets/js/auth-transition.js"></script>
</body>
</html>